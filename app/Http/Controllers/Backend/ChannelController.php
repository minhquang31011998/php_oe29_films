<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use App\Http\Requests\ChannelRequest;
use App\Repositories\Channel\ChannelRepositoryInterface;

class ChannelController extends Controller
{
    protected $channel;

    public function __construct(ChannelRepositoryInterface $channel)
    {
        $this->channel = $channel;
    }

    public function index()
    {
        return view('backend.channels.index');
    }

    public function getData(Request $request)
    {
        $data = $request->all();
        $channels = $this->channel->getChannels($data);

        return DataTables::of($channels->toArray())
            ->editColumn('id', function ($channel) {
                return '<div class="main__table-text">' . $channel['index'] . '</div>';
            })
            ->editColumn('title', function ($channel) {
                return '<div class="main__table-text">' . $channel['title'] . '</div>';
            })
            ->editColumn('status', function ($channel) {
                if ($channel['status'] == config('config.status_active')) {
                    return '<div class="main__table-text main__table-text--green">' . trans('active') . '</div>';
                } else {
                    return '<div class="main__table-text main__table-text--red">' . trans('hidden') . '</div>';
                }
            })
            ->addColumn('action', function ($channel) {
                return
                    '<div class="main__table-btns">
                        <a href="' . route('backend.channel.edit', $channel['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                            <i class="icon ion-ios-create"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['id', 'title', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $channelTypes = $this->channel->getChannelTypes();

        return view('backend.channels.create')->with([
            'channelTypes' => $channelTypes->toArray(),
        ]);
    }

    public function store(ChannelRequest $request)
    {
        $data = $request->all();
        $data['status'] = config('config.status_active');
        $data['user_id'] = Auth::user()->id;
        $this->channel->create($data);

        alert()->success(trans('created'), trans('success'));

        return redirect()->route('backend.channel.index');
    }

    public function edit($channelId)
    {
        $channel = $this->channel->find($channelId);
        $channelTypes = $this->channel->getChannelTypes();

        return view('backend.channels.edit')->with([
            'channelTypes' => $channelTypes->toArray(),
            'channel' => $channel->toArray(),
        ]);
    }

    public function update(ChannelRequest $request, $channelId)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $this->channel->update($channelId, $data);

        alert()->success(trans('updated'), trans('success'));

        return redirect()->route('backend.channel.index');
    }

    public function changeStatus($channelId)
    {
        return $this->channel->changeStatus($channelId);
    }

    public function destroy($channelId)
    {
        $this->channel->delete($channelId);
        alert()->success(trans('deleted'), trans('success'));

        return redirect()->route('backend.channel.index');
    }
}
