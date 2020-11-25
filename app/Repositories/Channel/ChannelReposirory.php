<?php
namespace App\Repositories\Channel;

use App\Models\Channel;
use App\Models\Option;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ChannelRepository extends BaseRepository implements ChannelRepositoryInterface
{
    public function getModel()
    {
        return Channel::class;
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
            $channel->user_id = Auth::user()->id;
            $channel->save();

            return $channel;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function getChannels($data = [])
    {
        $channels = Channel::select('id', 'title', 'status', 'created_at');
        if (isset($data['title'])) {
            $channels = $channels->where('title', 'like', "%" . $data['title'] . "%");
        }
        if (isset($data['sort'])) {
            if ($data['sort'] == trans('title')) {
                $channels = $channels->orderByRaw('title ASC');
            } elseif ($data['sort'] == trans('active')) {
                $channels = $channels->where('status', '=', config('config.status_active'));
            } elseif ($data['sort'] == trans('hidden')) {
                $channels = $channels->where('status', '=', config('config.status_hidden'));
            }
        }

        $channels = $channels->orderByRaw('created_at DESC')->get();

        foreach ($channels as $index => $channel) {
            $channel->index = $index + 1;
        }

        return $channels;
    }

    public function getChannelTypes()
    {
        $channelTypes = Option::where('name', '=', 'channel_type')
            ->first()
            ->optionValues()
            ->get();

        return $channelTypes;
    }
}
