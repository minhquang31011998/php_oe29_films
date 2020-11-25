<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Requests\PlaylistRequest;
use App\Repositories\Playlist\PlaylistRepositoryInterface;

class PlaylistController extends Controller
{
    protected $playlist;

    public function __construct(PlaylistRepositoryInterface $playlist)
    {
        $this->playlist = $playlist;
    }

    public function index()
    {
        session(['previousList' => request()->url()]);

        return view('backend.playlists.index');
    }

    public function getData(Request $request)
    {
        $playlists = $this->playlist->getPlaylists($request);

        return DataTables::of($playlists->toArray())
            ->editColumn('id', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['index'] . '</div>';
            })
            ->editColumn('title', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['title'] . '</div>';
            })
            ->editColumn('status', function ($playlist) {
                if ($playlist['status'] == config('config.status_active')) {
                    return '<div class="main__table-text main__table-text--green">' . trans('active') . '</div>';
                } else {
                    return '<div class="main__table-text main__table-text--red">' . trans('hidden') . '</div>';
                }
            })
            ->addColumn('action', function ($playlist) {
                return
                    '<div class="main__table-btns">
                        <a href="' . route('backend.playlist.edit', $playlist['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                            <i class="icon ion-ios-create"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['id', 'title', 'status', 'action'])
            ->make(true);
    }

    public function getPlaylistVideos(Request $request, $playlistId)
    {
        $videos = $this->playlist->getPlaylistVideos($request, $playlistId);

        return DataTables::of($videos->toArray())
            ->addColumn('action', function ($video) {
                return
                    '<div class="main__table-btns">
                        <a href="' . route('backend.video.edit', $video['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                            <i class="icon ion-ios-create"></i>
                        </a>
                        <button type="submit" class="main__table-btn main__table-btn--delete" onclick="detachVideo(' . $video['id'] . ')" data-toggle="tooltip" title="Detach">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </div>';
            })
            ->editColumn('id', function ($video) {
                return '<div class="main__table-text">' . $video['index'] . '</div>';
            })
            ->editColumn('title', function ($video) {
                return '<div class="main__table-text" data-toggle="tooltip" title="' . $video['description'] . '">' . $video['title'] . '</div>';
            })
            ->editColumn('chap', function ($video) {
                return '<div class="main__table-text">' . $video['chap'] . '</div>';
            })
            ->rawColumns(['id', 'title', 'chap', 'action'])
            ->make(true);
    }


    public function getVideos(Request $request)
    {
        $videos = $this->playlist->getVideos($request);

        return DataTables::of($videos->toArray())
            ->addColumn('action', function ($video) {
                return
                    '<div class="main__table-btns">
                        <input type="checkbox" title="videos[]" value="' . $video['id'] . '" id="checkboxVideo" onchange="chooseVideo(' . $video['id'] . ')">
                    </div>';
            })
            ->editColumn('id', function ($video) {
                return '<div class="main__table-text">' . $video['index'] . '</div>';
            })
            ->editColumn('title', function ($video) {
                return '<div class="main__table-text">' . $video['title'] . '</div>';
            })
            ->rawColumns(['action', 'id', 'title'])
            ->make(true);
    }

    public function create()
    {
        return view('backend.playlists.create');
    }

    public function store(PlaylistRequest $request)
    {
        $playlist = $this->playlist->storePlaylist($request);
        alert()->success(trans('created'), trans('success'));

        return redirect()->route('backend.playlist.edit', $playlist->id);
    }

    public function show($playlistId)
    {
        $playlist = $this->playlist->find($playlistId);

        return response()->json([
            'error' => false,
            'playlist'  => $playlist,
        ]);
    }

    public function edit($playlistId)
    {
        $playlist = $this->playlist->find($playlistId);
        $channels = $this->playlist->getChannels();

        session(['previous-url' => request()->url()]);

        return view('backend.playlists.edit')->with([
            'playlist' => $playlist->toArray(),
            'channels' => $channels->toArray(),
        ]);
    }

    public function update(PlaylistRequest $request, $playlistId)
    {
        $playlist = $this->playlist->updatePlaylist($request, $playlistId);
        if ($request->has('movie_id')) {
            return response()->json([
                'error' => false,
                'playlist'  => $playlist,
            ]);
        }
        Session::flash('status', 'new');
        alert()->success(trans('updated'), trans('success'));

        return redirect(session('previousList'));
    }

    public function chooseVideo(Request $request, $playlistId)
    {
        $this->playlist->chooseVideo($request, $playlistId);

        return redirect()->back();
    }

    public function destroy($playlistId)
    {
        $this->playlist->deletePlaylist($playlistId);
        alert()->success(trans('deleted'), trans('success'));

        return redirect()->route('backend.playlist.index');
    }

    public function detach($playlistId)
    {
        $this->playlist->detach($playlistId);
        Session::flash('status', 'new');

        return redirect()->back();
    }

    public function changeStatus($playlistId)
    {
        return $this->playlist->changeStatus($playlistId);
    }
}
