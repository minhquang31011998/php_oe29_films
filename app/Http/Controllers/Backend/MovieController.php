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
}
