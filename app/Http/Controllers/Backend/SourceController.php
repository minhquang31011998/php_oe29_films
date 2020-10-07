<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Models\Channel;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Http\Requests\SourceRequest;

class SourceController extends Controller
{
    public function store(SourceRequest $request)
    {
        try {
            Session::flash('status', 'new');

            $source = new Source();
            $source->video_id = $request->get('video_id');
            $source->channel_id = $request->get('channel_id');
            $source->user_id = 1;
            $source->source_key = $request->get('source_key');
            $videoSources = Video::findOrFail($source->video_id)
                ->sources()
                ->get()
                ->sortBy('prioritize');
            $source->prioritize = $this->sortSource($request->get('prioritize'), $videoSources);
            $source->save();
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

    public function edit($sourceId)
    {
        try {
            $source = Source::findOrFail($sourceId);

            return response()->json([
                'source'  => $source,
            ]);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function update(SourceRequest $request, $sourceId)
    {
        try {
            Session::flash('status', 'new');

            $source = Source::findOrFail($request->get('source_id'));
            $source->channel_id = $request->get('channel_id');
            $source->user_id = 1;
            $source->source_key = $request->get('source_key');
            $video = Video::findOrFail($source->video_id);
            $videoSources = $video
                ->sources()
                ->where('id', '!=', $request->get('source_id'))
                ->get()
                ->sortBy('prioritize');
            $source->prioritize = $this->sortSource($request->get('prioritize'), $videoSources);
            $source->save();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function destroy($sourceId)
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
        Source::destroy($sourceId);
    }
}
