<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\MovieRequest;
use App\Repositories\Movie\MovieRepositoryInterface;

class MovieController extends Controller
{
    protected $movie;

    public function __construct(MovieRepositoryInterface $movie)
    {
        $this->movie = $movie;
    }

    public function index()
    {
        return view('backend.movies.index');
    }

    public function getData(Request $request)
    {
        $data = $request->all();
        $movies = $this->movie->getMovies($data);

        return DataTables::of($movies->toArray())
            ->editColumn('id', function ($movie) {
                return '<div class="main__table-text">' . $movie['index'] . '</div>';
            })
            ->editColumn('name', function ($movie) {
                return '<div class="main__table-text">' . $movie['name'] . '</div>';
            })
            ->editColumn('slug', function ($movie) {
                return '<div class="main__table-text">' . $movie['slug'] . '</div>';
            })
            ->addColumn('action', function ($movie) {
                $nominations = $movie['nominations'] == config('config.nommination_off')
                    ?'class="main__table-btn main__table-btn--delete open-modal btn-nominations" data-toggle="tooltip" title="Turn on nomination">
                        <i class="icon ion-ios-radio-button-off"></i>'
                    :'class="main__table-btn main__table-btn--edit open-modal btn-nominations" data-toggle="tooltip" title="Turn off nomination">
                        <i class="icon ion-ios-radio-button-on"></i>';

                return
                    '<div class="main__table-btns">
                        <a href="#" movie_id="' . $movie['id'] . '" data_status="' . $movie['nominations'] . '"' . $nominations . '
                        </a>
                        <a href="' . route('backend.movie.edit', $movie['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                            <i class="icon ion-ios-create"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['id', 'name', 'slug', 'action'])
            ->make(true);
    }

    public function nominations($id)
    {
        return $movies = $this->movie->changeNomination($id);
    }

    public function getTags()
    {
        return $tags = $this->movie->getTags()->toArray();
    }

    /**
     * Lấy dữ liệu các playlist của movie hiện tại
     */
    public function getMoviePlaylists(Request $request, $movieId)
    {
        $data = $request->all();

        $playlists = $this->movie->getMoviePlaylists($movieId, $data);

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

    /**
     * Lấy dữ liệu các playlist chưa có movie
     */
    public function getPlaylists(Request $request)
    {
        $data = $request->all();

        $playlists = $this->movie->getPlaylists($data);

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
        $data = $request->all();

        $videos = $this->movie->getVideos($data);

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

    public function create()
    {
        $qualities = $this->movie->getOptionValue('quality');
        $genres = $this->movie->getOptionValue('genre');
        $countries = $this->movie->getOptionValue('country');
        $types = $this->movie->getTypes();

        return view('backend.movies.create')->with([
            'qualities' => $qualities->toArray(),
            'genres' => $genres->toArray(),
            'countries' => $countries->toArray(),
            'types' => $types->toArray(),
        ]);
    }

    public function store(MovieRequest $request)
    {
        $movie = $this->movie->storeMovie($request);
        $request->session()->flash('status', 'new');
        alert()->success(trans('created'), trans('step'));

        return redirect()->route('backend.movie.edit', $movie->id);
    }

    public function edit($movieId)
    {
        $movie = $this->movie->find($movieId);
        $qualities = $this->movie->getOptionValue('quality');
        $genres = $this->movie->getOptionValue('genre');
        $countries = $this->movie->getOptionValue('country');
        $channels = $this->movie->getChannels();
        $types = $this->movie->getTypes();
        $movieTags = $this->movie->getMovieTags($movie);

        $tag = '';
        foreach ($movieTags as $movieTag) {
            $tag .= $movieTag->name . ',';
        }
        $movieTags = trim($tag, ',');

        $movieTypes = $this->movie->getMovieTypes($movie);

        $movieVideos = $this->movie->getMovieVideos($movie);

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
            'movieVideos' => $movieVideos
        ]);
    }

    public function update(Request $request, $movieId)
    {
        $movie = $this->movie->updateMovie($movieId, $request);

        alert()->success(trans('updated'), trans('success'));

        return redirect()->route('backend.movie.index');
    }

    public function updatePlaylist(Request $request, $movieId)
    {
        return $this->movie->updatePlaylist($request, $movieId);
    }

    public function updateVideo(Request $request, $movieId)
    {
        Session::flash('status', 'new');

        return $this->movie->updateVideo($request, $movieId);
    }

    public function destroy($movieId)
    {
        $this->movie->deleteMovie($movieId);

        alert()->success(trans('deleted'), trans('success'));

        return redirect()->route('backend.movie.index');
    }
}
