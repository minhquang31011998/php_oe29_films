<?php

namespace App\Http\Controllers\Backend;

use App\Models\Channel;
use App\Models\Option;
use App\Models\OptionValue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use App\Http\Requests\ChannelRequest;

class ChannelController extends Controller
{
    public function index()
    {
        return view('backend.channels.index');
    }

    public function getData(Request $request)
    {
        $channels = Channel::select('id', 'title', 'status', 'created_at');
        if ($request->has('title')) {
            $channels = $channels->where('title', 'like', "%" . $request->get('title') . "%");
        }
        if ($request->has('sort')) {
            if ($request->get('sort') == trans('title')) {
                $channels = $channels->orderByRaw('title ASC');
            } elseif ($request->get('sort') == trans('active')) {
                $channels = $channels->where('status', '=', config('config.status_active'));
            } elseif ($request->get('sort') == trans('hidden')) {
                $channels = $channels->where('status', '=', config('config.status_hidden'));
            }
        }

        $channels = $channels->orderByRaw('created_at DESC');

        return DataTables::of($channels->get()->toArray())
            ->editColumn('id', function ($channel) {
                return '<div class="main__table-text">'. $channel['id'] .'</div>';
            })
            ->editColumn('title', function ($channel) {
                return '<div class="main__table-text">'. $channel['title'] .'</div>';
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
                    <form action="' . route('backend.channel.destroy', $channel['id']) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
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
        $channelTypes = Option::where('name', '=', 'channel_type')
            ->first()
            ->optionValues()
            ->get();

        return view('backend.channels.create')->with([
            'channelTypes' => $channelTypes->toArray(),
        ]);
    }

    public function store(ChannelRequest $request)
    {
        $channel = new Channel();
        $channel->title = $request->get('title');
        $channel->link = $request->get('link');
        $channel->description = $request->get('description');
        $channel->channel_type = $request->get('channel_type');
        $channel->status = config('config.status_active');
        $channel->user_id = 1;
        $channel->save();

        return redirect()->route('backend.channel.index');
    }

    public function edit($channelId)
    {
        try {
            $channel = Channel::findOrFail($channelId);
            $channelTypes = Option::where('name', '=', 'channel_type')
                ->first()
                ->optionValues()
                ->get();

            return view('backend.channels.edit')->with([
                'channelTypes' => $channelTypes->toArray(),
                'channel' => $channel->toArray(),
            ]);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function update(ChannelRequest $request, $channelId)
    {
        try {
            $channel = Channel::findOrFail($channelId);
            $channel->title = $request->get('title');
            $channel->link = $request->get('link');
            $channel->description = $request->get('description');
            $channel->channel_type = $request->get('channel_type');
            $channel->user_id = 1;
            $channel->save();

            return redirect()->route('backend.channel.index');
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function changeStatus($channelId)
    {
        try {
            $channel = Channel::findOrFail($channelId);
            if ($channel->status == config('config.status_active')) {
                $channel->status = config('config.status_hidden');
            } else {
                $channel->status = config('config.status_active');
            }
            $channel->user_id = 1;
            $channel->save();

            return $channel;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function destroy($channelId)
    {
        $channel = Channel::destroy($channelId);

        return redirect()->route('backend.channel.index');
    }
}
