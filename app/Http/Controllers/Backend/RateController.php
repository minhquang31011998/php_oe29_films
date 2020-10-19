<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Rate;
use App\Models\Movie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function rate(Request $request)
    {
        $oldRate = Rate::where([
            ['movie_id', $request->get('movie_id')],
            ['user_id', Auth::user()->id],
        ])->first();
        if ($oldRate == null) {
            $rate = new Rate();
            $rate->point = $request->get('star');
            $rate->user_id = Auth::user()->id;
            $rate->movie_id = $request->get('movie_id');
            $rate->save();
        } else {
            $oldRate->point = $request->get('star');
            $oldRate->save();
        }
        $this->takeAverageRate($request->get('movie_id'));
    }

    public function takeAverageRate($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $movieRates = $movie->rates()->get();
            if ($movieRates != null) {
                $averageRate = null;
                foreach ($movieRates as $movieRate) {
                    $averageRate += $movieRate->point;
                }
                $movie->rate = number_format($averageRate / count($movieRates), config('config.number_after_float'));
                $movie->save();
            }
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }
}
