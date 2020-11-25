<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\VideoRequest;
use App\Repositories\Video\VideoRepositoryInterface;

class VideoController extends Controller
{
    protected $video;

    public function __construct(VideoRepositoryInterface $video)
    {
        $this->video = $video;
    }

    public function index()
    {
        session(['previous-url' => request()->url()]);

        return view('backend.videos.index');
    }

    public function getData(Request $request)
    {
        $videos = $this->video->getVideos($request);

        return DataTables::of($videos->toArray())
            ->editColumn('id', function ($video) {
                return '<div class="main__table-text">' . $video['index'] . '</div>';
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
                    </div>';
            })
            ->rawColumns(['id', 'title', 'status', 'action'])
            ->make(true);
    }

    public function getSources(Request $request)
    {
        $sources = $this->video->getSources($request);

        return DataTables::of($sources->toArray())
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
                return '<div class="main__table-text">' . $source['index'] . '</div>';
            })
            ->editColumn('source_key', function ($source) {
                return '<div class="main__table-text">' . $source['source_key'] . '</div>';
            })
            ->editColumn('prioritize', function ($source) {
                return '<div class="main__table-text">' . $source['prioritize'] . '</div>';
            })
            ->editColumn('channel_id', function ($source) {
                $channel = $this->video->findChannel($source['channel_id']);

                return '<div class="main__table-text">' . $channel['title'] . '</div>';
            })
            ->rawColumns(['id', 'source_key', 'prioritize', 'channel_id', 'action'])
            ->make(true);
    }

    public function create()
    {
        $channels = $this->video->getChannels();

        return view('backend.videos.create')->with([
            'channels' => $channels->toArray()
        ]);
    }

    public function store(VideoRequest $request)
    {
        $this->video->storeVideo($request);
        alert()->success(trans('created'), trans('success'));

        return redirect()->route('backend.video.index');
    }

    public function edit($videoId)
    {
        $video = $this->video->findVideo($videoId);
        $channels = $this->video->getChannels();

        return view('backend.videos.edit')->with([
            'video' => $video->toArray(),
            'channels' => $channels
        ]);
    }

    public function update(VideoRequest $request, $videoId)
    {
        $this->video->updateVideo($request, $videoId);
        Session::flash('status', 'new');
        alert()->success(trans('updated'), trans('success'));

        return redirect(session('previous-url'));
    }

    public function changeStatus($videoId)
    {
        return $this->video->changeStatus($videoId);
    }

    public function detach($videoId)
    {
        $this->video->detach($videoId);

        if (session()->has('previousList')) {
            return redirect(session('previousList'));
        }
    }

    public function destroy($videoId)
    {
        $video = $this->video->delete($videoId);
        alert()->success(trans('deleted'), trans('success'));

        return redirect()->route('backend.video.index');
    }
}
