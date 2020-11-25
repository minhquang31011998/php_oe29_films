<?php
namespace App\Repositories\Rate;

interface RateRepositoryInterface
{
    public function findRate($movieId, $userId);

    public function updateMovie($id, $rate);

    public function findMovie($id);

    public function findMovieRate($id);
}
