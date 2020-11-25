<?php
namespace App\Repositories\Playlist;

interface PlaylistRepositoryInterface
{
    public function getPlaylists($request);

    public function getPlaylistVideos($request, $playlistId);

    public function getVideos($request);

    public function storePlaylist($request);

    public function getChannels();

    public function updatePlaylist($request, $playlistId);

    public function chooseVideo($request, $playlistId);

    public function deletePlaylist($playlistId);

    public function detach($playlistId);

    public function changeStatus($id);
}
