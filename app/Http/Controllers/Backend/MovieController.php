<?php

namespace App\Http\Controllers\Backend;

use App\Models\Movie;
use App\Models\Tag;
use App\Models\Type;
use App\Models\Video;
use App\Models\Source;
use App\Models\Channel;
use App\Models\Playlist;
use App\Models\Option;
use App\Models\OptionValue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Requests\MovieRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('backend.movies.index');
    }

    public function getData(Request $request)
    {
        $movies = Movie::select('id', 'name', 'nominations', 'slug', 'created_at');

        if ($request->has('name')) {
            $movies = $movies->where('name', 'like', "%" . $request->get('name') . "%");
        }

        if ($request->has('sort')) {
            if ($request->get('sort') == trans('name')) {
                $movies = $movies->orderByRaw('name ASC');
            } elseif ($request->get('sort') == trans('release_year')) {
                $movies = $movies->orderByRaw('release_year DESC');
            } elseif ($request->get('sort') == trans('rate')) {
                $movies = $movies->orderByRaw('rate DESC');
            } elseif ($request->get('sort') == trans('nomination')) {
                $movies = $movies->orderByRaw('nominations DESC');
            }
        }
        $movies = $movies->orderByRaw('created_at DESC');

        return DataTables::of($movies->get()->toArray())
            ->editColumn('id', function ($movie) {
                return '<div class="main__table-text">' . $movie['id'] . '</div>';
            })
            ->editColumn('name', function ($movie) {
                return '<div class="main__table-text">' . $movie['name'] . '</div>';
            })
            ->editColumn('slug', function ($movie) {
                return '<div class="main__table-text">' . $movie['slug'] . '</div>';
            })
            ->addColumn('action', function ($movie) {
                $nominations = $movie['nominations'] == config('config.nommination_off')
                    ? 'class="main__table-btn main__table-btn--delete open-modal btn-nominations" data-toggle="tooltip" title="Turn on nomination">
                    <i class="icon ion-ios-radio-button-off"></i>'
                    : 'class="main__table-btn main__table-btn--edit open-modal btn-nominations" data-toggle="tooltip" title="Turn off nomination">
                    <i class="icon ion-ios-radio-button-on"></i>';

                return
                    '<div class="main__table-btns">
                        <a href="#" movie_id="' . $movie['id'] . '" data_status="' . $movie['nominations'] . '"' . $nominations . '
                        </a>
                        <a href="' . route('backend.movie.edit', $movie['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                            <i class="icon ion-ios-create"></i>
                        </a>
                        <form action="' . route('backend.movie.destroy', $movie['id']) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                                <i class="icon ion-ios-trash"></i>
                            </button>
                        </form>
                    </div>';
            })
            ->rawColumns(['id', 'name', 'slug', 'action'])
            ->make(true);
    }

    public function nominations($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            if ($movie->nominations == config('config.nomination_on')) {
                $movie->nominations = config('config.nomination_off');
            }
            $movie->nominations = config('config.nomination_on');
            $movie->save();

            return $movie;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $qualities = $this->getOptionValue('quality');
        $genres = $this->getOptionValue('genre');
        $countries = $this->getOptionValue('country');
        $types = Type::get();

        return view('backend.movies.create')->with([
            'qualities' => $qualities->toArray(),
            'genres' => $genres->toArray(),
            'countries' => $countries->toArray(),
            'types' => $types->toArray(),
        ]);
    }

    public function getOptionValue($name)
    {
        $option = Option::where('name', $name)->first();
        $optionValues = $option->optionValues()->get();

        return $optionValues;
    }

    public function store(MovieRequest $request)
    {
        DB::beginTransaction();
        try {
            $movie = new Movie();
            $movie->description = $request->get('description');
            $movie->name = $request->get('name');
            $movie->name_origin = $request->get('name_origin');
            $movie->age = $request->get('age');
            $movie->genre = $request->get('genre');
            $movie->runtime = $request->get('runtime');
            $movie->release_year = $request->get('release_year');
            $movie->quality = $request->get('quality');
            $movie->country = $request->get('country');
            $movie->card_cover = '';
            $movie->user_id = 1;

            if ($request->hasFile('card_cover')) {
                $movie->card_cover = $this->storeImages($request->file('card_cover'), $movie);
            }
            $movie->slug = Str::slug($request->get('name'));
            $oldMovie = Movie::where('slug', $movie->slug)->first();

            if ($oldMovie == null) {
                $movie->slug = Str::slug($request->get('name'));
            } else {
                $movie->slug = $oldMovie->slug . '-' . Str::random(3);
            }
            $movie->save();

            $types = $request->get('types');
            $movie->types()->attach($types);

            $this->storeTags($request->get('tags'), $movie);

            DB::commit();
            $request->session()->flash('status', trans('create_success'));

            return redirect()->route('backend.movie.edit', $movie->id);
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back();
        }
    }

    public function storeTags($tags, $movie)
    {
        $tags = explode(',', $tags);
        for ($i = 0; $i < count($tags); $i++) {
            $tag = Tag::where('name', $tags[$i])->first();
            if ($tag !== null) {
                $movie->tags()->attach($tag);
            } else {
                $tag = new Tag();
                $tag->name = $tags[$i];
                $tag->slug = Str::slug($tags[$i]) . time();
                $tag->save();

                $movie->tags()->attach($tag);
            }
        }
    }

    public function storeImages($cardCover, $movie)
    {
        $name = $movie->id . '.' . $cardCover->getClientOriginalName();
        $cardCover->storeAs(config('config.store_images_movie'), $name);
        $movie->card_cover = config('config.link_images_movie') . $name;

        return $movie->card_cover;
    }

    public function getTags(Request $request)
    {
        $tags = Tag::get();
        return $tags->toArray();
    }

    public function getMoviePlaylists(Request $request, $movieId)
    {
        $playlists = Playlist::select('id', 'title', 'order', 'description', 'movie_id')
            ->orderByRaw('playlists.order ASC')
            ->where('movie_id', '=', $movieId);

        if ($request->has('title')) {
            $playlists = $playlists->where('title', 'like', "%" . $request->get('title') . "%");
        }

        $playlists = $playlists->get();

        foreach ($playlists as $index => $playlist) {
            $playlist->index = $index + 1;
        }

        return DataTables::of($playlists->toArray())
            ->addColumn('action', function ($playlist) {
                return
                    '<div class="main__table-btns">
                        <button class="main__table-btn main__table-btn--edit edit-playlist" data-toggle="tooltip" title="Edit Playlist" data-playlist="'. $playlist['id'] .'">
                            <i class="icon ion-ios-create"></i>
                        </button>
                        <a href="' . route('backend.playlist.edit', [$playlist['id'], $playlist['movie_id']]) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Videos">
                            <i class="ion ion-ios-videocam"></i>
                        </a>
                        <button type="submit" class="main__table-btn main__table-btn--delete" onclick="detachPlaylist(' . $playlist['id'] . ')" data-toggle="tooltip" title="Detach">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                        <button class="main__table-btn main__table-btn--delete open-modal delete-playlist" data-toggle="tooltip" title="Delete" onclick="deletePlaylist(' . $playlist['id'] . ')" >
                            <i class="icon ion-ios-trash"></i>
                        </button>
                    </div>';
            })
            ->editColumn('id', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['index'] . '</div>';
            })
            ->editColumn('title', function ($playlist) {
                return '<div class="main__table-text" data-toggle="tooltip" title="' . $playlist['description'] . '">' . $playlist['title'] . '</div>';
            })
            ->editColumn('order', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['order'] . '</div>';
            })
            ->rawColumns(['id', 'action', 'title', 'order'])
            ->make(true);
    }

    public function getPlaylists(Request $request)
    {
        $playlists = Playlist::select('id', 'title', 'order', 'description')
            ->orderByRaw('playlists.order ASC')
            ->where('movie_id', '=', null);

        if ($request->has('title')) {
            $playlists = $playlists->where('title', 'like', "%" . $request->get('title') . "%");
        }

        $playlists = $playlists->get();

        foreach ($playlists as $index => $playlist) {
            $playlist->index = $index + 1;
        }

        return DataTables::of($playlists->toArray())
            ->addColumn('action', function ($playlist) {
                return
                    '<div class="main__table-btns">
                        <input type="checkbox" title="playlists[]" class="check-list" value="' . $playlist['id'] . '" onchange="choosePlaylist(' . $playlist['id'] . ')">
                    </div>';
            })
            ->editColumn('id', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['index'] . '</div>';
            })
            ->editColumn('title', function ($playlist) {
                return '<div class="main__table-text" data-toggle="tooltip" title="' . $playlist['description'] . '">' . $playlist['title'] . '</div>';
            })
            ->editColumn('order', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['order'] . '</div>';
            })
            ->rawColumns(['action', 'id', 'title', 'order'])
            ->make(true);
    }

    public function getVideos(Request $request)
    {
        $videos = Video::select('id', 'title', 'description')
            ->where('movie_id', '=', null);

        if ($request->has('title')) {
            $videos = $videos->where('title', 'like', "%" . $request->get('title') . "%");
        }

        $videos = $videos->get();

        foreach ($videos as $index => $video) {
            $video->index = $index + 1;
        }

        return DataTables::of($videos->toArray())
            ->addColumn('action', function ($video) {
                return
                    '<div class="main__table-btns">
                        <input type="checkbox" title="videos[]" value="' . $video['id'] . '" class="check-list" onchange="chooseVideo(' . $video['id'] . ')">
                    </div>';
            })
            ->editColumn('id', function ($video) {
                return '<div class="main__table-text">' . $video['index'] . '</div>';
            })
            ->editColumn('title', function ($video) {
                return '<div class="main__table-text">' . $video['title'] . '</div>';
            })
            ->rawColumns(['id', 'action', 'title', 'description'])
            ->make(true);
    }

    public function edit($movieId)
    {
        try {
            $movie = Movie::findOrFail($movieId);
            $qualities = $this->getOptionValue('quality');
            $genres = $this->getOptionValue('genre');
            $countries = $this->getOptionValue('country');
            $channels = Channel::get();
            $types = Type::get();
            $movieTags = $movie->tags()->get();
            $tag = '';
            foreach ($movieTags as $movieTag) {
                $tag .= $movieTag->name . ',';
            }
            $movieTags = trim($tag, ',');
            $movieTypes = $movie
                ->types()
                ->get();
            $videos = Video::where('movie_id', '=', '')
                ->where('playlist_id', '=', '')
                ->get();
            $movieVideos = $movie->videos()->get();

            session(['previousList' => request()->url()]);

            return view('backend.movies.edit')->with([
                'movie' => $movie->toArray(),
                'qualities' => $qualities->toArray(),
                'genres' => $genres->toArray(),
                'countries' => $countries->toArray(),
                'channels' => $channels->toArray(),
                'types' => $types->toArray(),
                'movieTags' => $movieTags,
                'movieTypes' => $movieTypes->toArray(),
                'videos' => $videos,
                'movieVideos' => $movieVideos
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('backend.movie.index')->withErrors(['msg', $e->getMessage()]);
        }
    }

    public function update(Request $request, $movieId)
    {
        try {
            $movie = Movie::findOrFail($movieId);
            $movie->name = $request->get('name');
            $movie->name_origin = $request->get('name_origin');
            $movie->description = $request->get('description');
            $movie->age = $request->get('age');
            $movie->runtime = $request->get('runtime');
            $movie->release_year = $request->get('release_year');
            $movie->quality = $request->get('quality');
            $movie->country = $request->get('country');
            $movie->user_id = 1;

            if ($request->hasFile('card_cover')) {
                $movie->card_cover = $this->updateImage($request->file('card_cover'), $movie);
            }
            $movie->save();

            $this->updateTags($request->get('tags'), $movie);

            $movie->types()->detach();
            $types = $request->get('types');
            $movie->types()->attach($types);

            return redirect()->route('backend.movie.index');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('backend.movie.edit')->withErrors(['msg', $e->getMessage()]);
        }
    }

    public function updateTags($tags, $movie)
    {
        $movie->tags()->detach();
        $this->storeTags($tags, $movie);
    }

    public function updateImage($cardCover, $movie)
    {
        $name = $movie->id . '.' . $cardCover->getClientOriginalName();
        $link = str_replace(config('config.link_images_movie'), '', $movie->card_cover);
        Storage::delete(config('config.image_store') . $link);
        $cardCover->storeAs(config('config.store_images_movie'), $name);
        $movie->card_cover = config('config.link_images_movie') . $name;

        return $movie->card_cover;
    }

    public function updatePlaylist(Request $request, $movieId)
    {
        try {
            $moviePlaylists = Movie::findOrFail($movieId)
                ->playlists()
                ->get()
                ->sortBy('order');
            $playlist = Playlist::findOrFail($request->get('playlistId'));
            $playlist->movie_id = $movieId;
            $i = $playlist->order;
            foreach ($moviePlaylists as $moviePlaylist) {
                if ($moviePlaylist->order >= $playlist->order) {
                    $moviePlaylist->order = ++$i;
                    $moviePlaylist->save();
                }
            }
            $playlist->user_id = 1;
            $playlist->save();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function updateVideo(Request $request, $movieId)
    {
        try {
            $video = Video::findOrFail($request->get('videoId'));
            $video->movie_id = $movieId;
            $video->user_id = 1;
            $video->save();
            Session::flash('status', 'new');
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function destroy($movieId)
    {
        Movie::destroy($movieId);

        return redirect()->route('backend.movie.index');
    }
}
