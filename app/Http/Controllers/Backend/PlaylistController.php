<?php

namespace App\Http\Controllers\Backend;

use App\Models\Playlist;
use App\Models\Video;
use App\Models\Movie;
use App\Models\Channel;
use App\Models\Source;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Requests\PlaylistRequest;

class PlaylistController extends Controller
{
    public function index()
    {
        return view('backend.playlists.index');
    }

    public function getData(Request $request)
    {
        $playlists = Playlist::select('id', 'title', 'status', 'created_at');

        if ($request->has('title')) {
            $playlists = $playlists->where('title', 'like', "%" . $request->get('title') . "%");
        }

        if ($request->has('sort')) {
            if ($request->get('sort') == trans('title')) {
                $playlists = $playlists->orderByRaw('title ASC');
            } elseif ($request->get('sort') == trans('date_created')) {
                $playlists = $playlists->orderByRaw('created_at DESC');
            } elseif ($request->get('sort') == trans('active')) {
                $playlists = $playlists->where('status', '=', config('config.status_active'));
            } elseif ($request->get('sort') == trans('hidden')) {
                $playlists = $playlists->where('status', '=', config('config.status_hidden'));
            }
        }

        return DataTables::of($playlists->get()->toArray())
            ->editColumn('id', function ($playlist) {
                return '<div class="main__table-text">' . $playlist['id'] . '</div>';
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
                        <form action="'. route('backend.playlist.destroy', $playlist['id']) . '" method="POST">
                            '. csrf_field() .'
                            '. method_field('DELETE') .'
                            <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                                <i class="icon ion-ios-trash"></i>
                            </button>
                        </form>
                    </div>';
            })
            ->rawColumns(['id', 'title', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('backend.playlists.create');
    }

    public function store(PlaylistRequest $request)
    {
        try {
            $playlist = new Playlist();
            $playlist->title = $request->get('title');
            $playlist->description = $request->get('description');
            $playlist->order = $request->get('order');
            if ($request->has('movie_id')) {
                $playlist->movie_id = $request->get('movie_id');
                $moviePlaylists = Movie::findOrFail($playlist->movie_id)
                    ->playlists()
                    ->get()
                    ->sortBy('order');
                foreach ($moviePlaylists as $moviePlaylist) {
                    if ($moviePlaylist->order >= $playlist->order) {
                        $moviePlaylist->order++;
                        $moviePlaylist->save();
                    }
                }
            }
            $playlist->user_id = 1;
            $playlist->save();

            return redirect()->route('backend.playlist.edit', $playlist->id);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function getPlaylistVideos(Request $request, $playlistId)
    {
        $videos = Video::select('id', 'title', 'description', 'chap', 'playlist_id')
            ->where('playlist_id', '=', $playlistId)
            ->orderByRaw('chap ASC')
            ->get();

        foreach ($videos as $index => $video) {
            $video->index = $index + 1;
        }

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
        $videos = Video::select('id', 'title')
            ->where('movie_id', '=', null)
            ->where('playlist_id', '=', null)
            ->get();

        foreach ($videos as $index => $video) {
            $video->index = $index + 1;
        }

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

    public function show($playlistId)
    {
        try {
            $playlist = Playlist::findOrFail($playlistId);
            return response()->json([
                'error' => false,
                'playlist'  => $playlist,
            ]);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function edit($playlistId)
    {
        try {
            $playlist = Playlist::findOrFail($playlistId);
            $channels = Channel::get();

            session(['previous-url' => request()->url()]);

            return view('backend.playlists.edit')->with([
                'playlist' => $playlist->toArray(),
                'channels' => $channels->toArray(),
            ]);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function update(PlaylistRequest $request, $playlistId)
    {
        try {
            $playlist = Playlist::findOrFail($playlistId);
            $playlist->title = $request->get('title');
            $playlist->description = $request->get('description');
            $playlist->order = $request->get('order');
            if ($playlist->movie_id != null) {
                $moviePlaylists = Movie::findOrFail($playlist->movie_id)
                    ->playlists()
                    ->where('id', '!=', $playlistId)
                    ->get()
                    ->sortBy('order');
                $i = $playlist->order;
                foreach ($moviePlaylists as $moviePlaylist) {
                    if ($moviePlaylist->order >= $playlist->order) {
                        $moviePlaylist->order = ++$i;
                        $moviePlaylist->save();
                    }
                }
            }
            $playlist->user_id = 1;
            $playlist->save();
            if ($request->has('movie_id')) {
                return response()->json([
                    'error' => false,
                    'playlist'  => $playlist,
                ]);
            }

            return redirect()->route('backend.playlist.index');
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function chooseVideo(Request $request, $playlistId)
    {
        $playlist = Playlist::findOrFail($playlistId);
        $playlistVideos = $playlist->videos()
            ->get()
            ->sortBy('chap');
        $video = Video::findOrFail($request->get('videoId'));
        $video->playlist_id = $playlistId;

        if ($video->chap == null) {
            $video->chap = config('config.default_prioritize');
            foreach ($playlistVideos as $playlistVideo) {
                if ($video->chap == $playlistVideo->chap) {
                    $video->chap++;
                }
            }
        } else {
            $i = $video->chap;
            foreach ($playlistVideos as $playlistVideo) {
                if ($playlistVideo->chap >= $video->chap) {
                    $playlistVideo->chap = ++$i;
                    $playlistVideo->save();
                }
            }
        }
        $video->save();

        return redirect()->back();
    }

    public function destroy($playlistId)
    {
        try {
            $playlist = Playlist::destroy($playlistId);

            return redirect()->route('backend.playlist.index');
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function detach($playlistId)
    {
        try {
            $playlist = Playlist::findOrFail($playlistId);
            $playlist->movie_id = null;
            $playlist->save();
            Session::flash('status', 'new');

            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function changeStatus($playlistId)
    {
        try {
            $playlist = Playlist::findOrFail($playlistId);
            if ($playlist->status == config('config.status_active')) {
                $playlist->status = config('config.status_hidden');
            } else {
                $playlist->status = config('config.status_active');
            }
            $playlist->user_id = 1;
            $playlist->save();

            return $playlist;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }
}
