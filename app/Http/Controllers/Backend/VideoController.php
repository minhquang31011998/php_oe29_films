<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        session(['previous-url' => request()->url()]);

        return view('backend.videos.index');
    }

    public function getData(Request $request)
    {
        $videos = Video::select('id', 'title', 'status', 'created_at');

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

        return DataTables::of($videos->get()->toArray())
            ->editColumn('id', function ($video) {
                return '<div class="main__table-text">' . $video['id'] . '</div>';
            })
            ->editColumn('title', function ($video) {
                return '<div class="main__table-text">' . $video['title'] . '</div>';
            })
            ->editColumn('status', function ($video) {
                if ($video['status'] == config('config.status_active')) {
                    return '<div class="main__table-text main__table-text--green">' . trans('active') . '</div>';
                } else {
                    return '<div class="main__table-text main__table-text--red">' . trans('hidden') . '</div>';
                }
            })

            ->addColumn('action', function ($video) {
                return
                '<div class="main__table-btns">
                    <a href="' . route('backend.video.edit', $video['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="' . trans('edit') . '">
                    <i class="icon ion-ios-create"></i>
                    </a>
                    <form action="' . route('backend.video.destroy', $video['id']) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="main__table-btn main__table-btn--delete">
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
        $channels = Channel::get();

        return view('backend.videos.create')->with([
            'channels'=>$channels->toArray()
        ]);
    }

    public function store(VideoRequest $request)
    {
        $video = new Video();
        $video->title = $request->get('title');
        $video->tags = $request->get('tags');
        $video->description = $request->get('description');
        $video->user_id = 1;
        $oldVideo = Video::where('slug', $video->slug)->first();
        if ($oldVideo == null) {
            $video->slug = Str::slug($request->get('title'));
        } else {
            $video->slug = $oldMovie->slug . '-' . Str::random(3);
        }
        $video->save();

        $this->storeSource($request->get('channel_id'), $request->get('source_key'), $video);

        return redirect()->route('backend.video.index');
    }

    public function storeSource($channel, $sourceKey, $video)
    {
        $source = new Source();
        $source->video_id = $video->id;
        $source->prioritize = config('config.default_prioritize');
        $source->channel_id = $channel;
        $source->user_id = 1;
        $source->source_key = $sourceKey;
        $source->save();
    }

    public function getSources(Request $request)
    {
        $sources = Source::select('id', 'source_key', 'prioritize', 'channel_id')
            ->where('video_id', '=', $request->get('videoId'))
            ->orderByRaw('prioritize ASC');

        return DataTables::of($sources->get()->toArray())
            ->addColumn('action', function ($source) {
                return
                    '<div class="main__table-btns">
                        <button class="main__table-btn main__table-btn--edit edit-source" title="Edit" data-source="' . $source['id'] . '">
                            <i class="icon ion-ios-create"></i>
                        </button>

                        <button class="main__table-btn main__table-btn--delete delete_source" value="' . $source['id'] . '" title="Delete" onclick="deleteSource(' . $source['id'] . ')">
                            <i class="icon ion-ios-trash"></i>
                        </button>
                    </div>';
            })
            ->editColumn('id', function ($source) {
                return '<div class="main__table-text">' . $source['id'] . '</div>';
            })
            ->editColumn('source_key', function ($source) {
                return '<div class="main__table-text">' . $source['source_key'] . '</div>';
            })
            ->editColumn('prioritize', function ($source) {
                return '<div class="main__table-text">' . $source['prioritize'] . '</div>';
            })
            ->editColumn('channel_id', function ($source) {
                $channel = Channel::select('title')->where('id', '=', $source['channel_id'])->first();

                return '<div class="main__table-text">' . $channel['title'] . '</div>';
            })
            ->rawColumns(['id', 'source_key', 'prioritize', 'channel_id', 'action'])
            ->make(true);
    }

    public function edit($videoId)
    {
        try {
            $video = Video::findOrFail($videoId);
            $channels = Channel::get();
            if ($video->movie_id != null) {
                $video->movie = $video->movie()->first();
            } else {
                $video->movie = null;
            }

            return view('backend.videos.edit')->with([
                'video'=>$video->toArray(),
                'channels'=>$channels
            ]);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function update(VideoUpdateRequest $request, $videoId)
    {
        try {
            $video = Video::findOrFail($videoId);

            $video->title = $request->get('title');
            $video->tags = $request->get('tags');
            $video->description = $request->get('description');
            $video->chap = $request->get('chap');
            if ($video->playlist_id != null && $request->has('chap') && $video->playlist_id != null) {
                $playlistVideos = Playlist::findOrFail($video->playlist_id)
                    ->videos()
                    ->where('id', '!=', $request->get('videoId'))
                    ->get()
                    ->sortBy('chap');
                $this->sortChap($request->get('chap'), $playlistVideos);
            }
            $video->user_id = 1;
            $oldVideo = Video::where('slug', $video->slug)->first();
            if ($oldVideo == null) {
                $video->slug = Str::slug($request->get('title'));
            } else {
                $video->slug = $oldVideo->slug . '-' . Str::random(3);
            }
            $video->save();

            Session::flash('status', 'new');

            return redirect(session('previous-url'));
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

    public function changeStatus($sourceId)
    {
        try {
            $video = Video::findOrFail($sourceId);
            if ($video->status == config('config.status_active')) {
                $video->status = config('config.status_hidden');
            } else {
                $video->status = config('config.status_active');
            }
            $video->user_id = 1;
            $video->save();

            return $video;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function detach($videoId)
    {
        $video = Video::findOrFail($videoId);
        $video->playlist_id = null;
        $video->movie_id = null;
        $video->save();

        return redirect()->back();
    }

    public function destroy($videoId)
    {
        $video = Video::destroy($videoId);

        return redirect()->route('backend.video.index');
    }
}
