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
}
