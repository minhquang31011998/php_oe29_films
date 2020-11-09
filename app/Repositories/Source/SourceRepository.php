<?php
namespace App\Repositories\Source;

use App\Models\Source;
use App\Models\Channel;
use App\Models\Video;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class SourceRepository extends BaseRepository implements SourceRepositoryInterface
{
    public function getModel()
    {
        return Source::class;
    }

    public function storeSource($request)
    {
        try {
            $source = new Source();
            $source->video_id = $request->get('video_id');
            $source->channel_id = $request->get('channel_id');
            $source->user_id = Auth::user()->id;
            $source->source_key = $request->get('source_key');
            $videoSources = Video::findOrFail($source->video_id)
                ->sources()
                ->get()
                ->sortBy('prioritize');
            $source->prioritize = $this->sortSource($request->get('prioritize'), $videoSources);

            return $source->save();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function sortSource($prioritize, $videoSources)
    {
        if ($prioritize == null) {
            $prioritize = config('config.default_prioritize');
            foreach ($videoSources as $videoSource) {
                if ($prioritize == $videoSource->prioritize) {
                    $prioritize++;
                }
            }
        } else {
            $prior = $prioritize;
            foreach ($videoSources as $videoSource) {
                if ($videoSource->prioritize >= $prioritize) {
                    $videoSource->prioritize = ++$prior;
                    $videoSource->save();
                }
            }
        }

        return $prioritize;
    }

    public function updateSource($request, $sourceId)
    {
        try {
            $source = Source::findOrFail($request->get('source_id'));
            $source->channel_id = $request->get('channel_id');
            $source->user_id = Auth::user()->id;
            $source->source_key = $request->get('source_key');
            $video = Video::findOrFail($source->video_id);
            $videoSources = $video
                ->sources()
                ->where('id', '!=', $request->get('source_id'))
                ->get()
                ->sortBy('prioritize');
            $source->prioritize = $this->sortSource($request->get('prioritize'), $videoSources);
            return $source->save();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function deleteSource($sourceId)
    {
        $source = Source::findOrFail($sourceId);
        $videoSources = Video::findOrFail($source->video_id)
            ->sources()
            ->get()
            ->sortBy('prioritize');
        $prioritize = $source->prioritize;

        foreach ($videoSources as $videoSource) {
            if ($videoSource->prioritize > $source->prioritize) {
                $videoSource->prioritize = $prioritize++;
                $videoSource->save();
            }
        }

        return Source::destroy($sourceId);
    }
}
