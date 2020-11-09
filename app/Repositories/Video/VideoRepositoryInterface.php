<?php
namespace App\Repositories\Video;

interface VideoRepositoryInterface
{
    public function getVideos($request);

    public function getSources($request);

    public function getChannels();

    public function storeVideo($request);

    public function updateVideo($request, $videoId);

    public function storeSource($channel, $sourceKey, $video);

    public function findVideo($videoId);

    public function findChannel($channelId);

    public function sortChap($chap, $playlistVideos);

    public function changeStatus($videoId);

    public function detach($videoId);
}
