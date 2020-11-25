<?php
namespace App\Repositories\Playlist;

use App\Models\Playlist;
use App\Models\Video;
use App\Models\Movie;
use App\Models\Channel;
use App\Models\Source;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class PlaylistRepository extends BaseRepository implements PlaylistRepositoryInterface
{
    public function getModel()
    {
        return Playlist::class;
    }

    public function getPlaylists($request)
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
        $playlists = $playlists->get();

        foreach ($playlists as $index => $playlist) {
            $playlist->index = $index + 1;
        }

        return $playlists;
    }

    public function getPlaylistVideos($request, $playlistId)
    {
        $videos = Video::select('id', 'title', 'description', 'chap', 'playlist_id')
            ->where('playlist_id', '=', $playlistId)
            ->orderByRaw('chap ASC')
            ->get();

        foreach ($videos as $index => $video) {
            $video->index = $index + 1;
        }

        return $videos;
    }

    public function getVideos($request)
    {
        $videos = Video::select('id', 'title')
            ->where('movie_id', '=', null)
            ->where('playlist_id', '=', null)
            ->get();

        foreach ($videos as $index => $video) {
            $video->index = $index + 1;
        }

        return $videos;
    }

    public function storePlaylist($request)
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
                $i = $playlist->order;
                foreach ($moviePlaylists as $moviePlaylist) {
                    if ($moviePlaylist->order >= $playlist->order) {
                        $moviePlaylist->order = ++$i;
                        $moviePlaylist->save();
                    }
                }
            }
            $playlist->user_id = Auth::user()->id;
            $playlist->save();

            return $playlist;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function getChannels()
    {
        return Channel::get();
    }

    public function updatePlaylist($request, $playlistId)
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
            $playlist->user_id = Auth::user()->id;

            return $playlist->save();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function chooseVideo($request, $playlistId)
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

        return $video->save();
    }

    public function deletePlaylist($playlistId)
    {
        try {
            $playlist = Playlist::findOrFail($playlistId);
            $video = $playlist->videos()->first();
            if ($video != null) {
                $video->playlist_id = null;
                $video->save();
            }
            return $playlist = Playlist::destroy($playlistId);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function detach($playlistId)
    {
        $playlist = Playlist::findOrFail($playlistId);
        $playlist->movie_id = null;

        return $playlist->save();
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
            $playlist->user_id = Auth::user()->id;
            $playlist->save();

            return $playlist;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }
}
