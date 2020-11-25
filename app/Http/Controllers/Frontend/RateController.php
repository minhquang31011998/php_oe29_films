<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Rate;
use App\Models\Movie;
use App\Http\Controllers\Controller;
use App\Repositories\Rate\RateRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    protected $rate;

    public function __construct(RateRepositoryInterface $rate)
    {
        $this->rate = $rate;
    }
    public function rate(Request $request)
    {
        $dataRate = $request->all();
        $dataRate['point'] = $request->get('star');
        $dataRate['user_id'] = Auth::user()->id;
        $oldRate = $this->rate->findRate($request->get('movie_id'), Auth::user()->id);
        if ($oldRate == null) {
            $this->rate->create($dataRate);
        } else {
            $this->rate->update($oldRate->id, $dataRate);
        }
        $result = $this->takeAverageRate($request->get('movie_id'));

        return $result;
    }

    public function takeAverageRate($id)
    {
        $movie = $this->rate->findMovie($id);
        $movieRates = $this->rate->findMovieRate($id);
        $sumRate = null;
        foreach ($movieRates as $movieRate) {
            $sumRate += $movieRate->point;
        }
        $movie->rate = number_format($sumRate / count($movieRates), config('config.number_after_float'));
        $result = $this->rate->updateMovie($id, $movie->rate);

        return $result;
    }
}
