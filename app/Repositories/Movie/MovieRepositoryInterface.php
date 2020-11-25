<?php
namespace App\Repositories\Movie;

interface MovieRepositoryInterface
{
    public function getMovies($attribute = []);

    public function changeNomination($id);

    public function getTags();

    public function getMoviePlaylists($movieId, $attribute = []);

    public function getPlaylists($attribute = []);

    public function getVideos($attribute = []);

    public function getOptionValue($name);

    public function storeMovie($request);

    public function storeImages($cardCover, $movie);

    public function storeTags($tags, $movie);

    public function getChannels();

    public function getTypes();

    public function getMovieTags($movie);

    public function getMovieTypes($movie);

    public function getMovieVideos($movie);

    public function updateMovie($movieId, $request);

    public function updatePlaylist($request, $movieId);

    public function updateVideo($request, $movieId);

    public function deleteMovie($movieId);

    public function getNewMovieInMonth();
}
