<?php
namespace App\Repositories\Rate;

use App\Models\Rate;
use App\Models\Movie;
use App\Repositories\BaseRepository;

class RateRepository extends BaseRepository implements RateRepositoryInterface
{
    public function getModel()
    {
        return Rate::class;
    }

    public function findRate($movieId, $userId)
    {
        $rate = Rate::where([
            ['movie_id', $movieId],
            ['user_id', $userId],
        ])->first();

        return $rate;
    }

    public function updateMovie($id, $rate)
    {
        $result = Movie::find($id);
        if ($result) {
            $result->update(['rate' => $rate]);
            return $result;
        }

        return false;
    }

    public function findMovie($id)
    {
        $result = Movie::findOrFail($id);

        return $result;
    }

    public function findMovieRate($id)
    {
        $movie = Movie::findOrFail($id);
        $result = $movie->rates()->get();

        return $result;
    }
}
