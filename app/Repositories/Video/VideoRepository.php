<?php
namespace App\Repositories\Video;

use App\Models\Video;
use App\Models\Source;
use App\Models\Channel;
use App\Models\Playlist;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    public function getModel()
    {
        return Video::class;
    }

    public function getVideos($request)
    {
        $videos = Video::select('id', 'title', 'status', 'created_at')
            ->orderByRaw('created_at DESC');

        if ($request->has('title')) {
            $videos = $videos->where('title', 'like', "%" . $request->get('title') . "%");
        }

        if ($request->has('sort')) {
            if ($request->get('sort') == trans('title')) {
                $videos = $videos->orderByRaw('title ASC');
            } elseif ($request->get('sort') == trans('date_created')) {
                $videos = $videos->orderByRaw('created_at DESC');
            } elseif ($request->get('sort') == trans('active')) {
                $videos = $videos->where('status', '=', config('config.status_active'));
            } elseif ($request->get('sort') == trans('hidden')) {
                $videos = $videos->where('status', '=', config('config.status_hidden'));
            }
        }

        $videos = $videos->get();

        foreach ($videos as $index => $video) {
            $video->index = $index + 1;
        }

        return $videos;
    }

    public function getSources($request)
    {
        $sources = Source::select('id', 'source_key', 'prioritize', 'channel_id')
            ->where('video_id', '=', $request->get('videoId'))
            ->orderByRaw('prioritize ASC')
            ->get();

        foreach ($sources as $index => $source) {
            $source->index = $index + 1;
        }

        return $sources;
    }

    public function getChannels()
    {
        return $channels = Channel::get();
    }

    public function storeVideo($request)
    {
        $video = new Video();
        $video->title = $request->get('title');
        $video->tags = $request->get('tags');
        $video->description = $request->get('description');
        $video->user_id = Auth::user()->id;
        $oldVideo = Video::where('slug', $video->slug)->first();
        if ($oldVideo == null) {
            $video->slug = Str::slug($request->get('title'));
        } else {
            $video->slug = $oldMovie->slug . '-' . Str::random(3);
        }
        if ($request->get('chap') != null) {
            $video->chap = $request->get('chap');
            $video->playlist_id = $request->get('playlist_id');
            if ($video->playlist_id != null && $request->has('chap')) {
                $playlistVideos = Playlist::findOrFail($video->playlist_id)
                    ->videos()
                    ->get()
                    ->sortBy('chap');
                $this->sortChap($request->get('chap'), $playlistVideos);
            }
        } else {
            $video->chap = config('config.default_chap');
        }
        $video->movie_id = $request->get('movie_id');
        $video->save();

        return $this->storeSource($request->get('channel_id'), $request->get('source_key'), $video);
    }

    public function storeSource($channel, $sourceKey, $video)
    {
        $source = new Source();
        $source->video_id = $video->id;
        $source->prioritize = config('config.default_prioritize');
        $source->channel_id = $channel;
        $source->user_id = Auth::user()->id;
        $source->source_key = $sourceKey;

        return $source->save();
    }

    public function findVideo($videoId)
    {
        $video = Video::findOrFail($videoId);
        if ($video->movie_id != null) {
            $video->movie = $video->movie()->first();
        } else {
            $video->movie = null;
        }

        return $video;
    }

    public function updateVideo($request, $videoId)
    {
        try {
            $video = Video::findOrFail($videoId);
            $video->title = $request->get('title');
            $video->tags = $request->get('tags');
            $video->description = $request->get('description');
            $video->chap = $request->get('chap');
            if ($video->playlist_id != null && $request->has('chap')) {
                $playlistVideos = Playlist::findOrFail($video->playlist_id)
                    ->videos()
                    ->where('id', '!=', $request->get('videoId'))
                    ->get()
                    ->sortBy('chap');
                $this->sortChap($request->get('chap'), $playlistVideos);
            }
            $video->user_id = Auth::user()->id;

            return $video->save();
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['msg', $e->getMessage()]);
        }
    }

    public function sortChap($chap, $playlistVideos)
    {
        $i = $chap;
        foreach ($playlistVideos as $playlistVideo) {
            if ($playlistVideo->chap >= $chap) {
                $playlistVideo->chap = ++$i;
                $playlistVideo->save();
            }
        }
    }

    public function changeStatus($videoId)
    {
        try {
            $video = Video::findOrFail($videoId);
            if ($video->status == config('config.status_active')) {
                $video->status = config('config.status_hidden');
            } else {
                $video->status = config('config.status_active');
            }
            $video->user_id = Auth::user()->id;
            $video->save();

            return $video;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function detach($videoId)
    {
        try {
            $video = Video::findOrFail($videoId);
            $video->playlist_id = null;
            $video->movie_id = null;

            return $video->save();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function findChannel($channelId)
    {
        return Channel::select('title')->where('id', '=', $channelId)->first();
    }
}
