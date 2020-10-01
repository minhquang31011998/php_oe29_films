<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
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
                if (config('config.status_active')) {
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


}
