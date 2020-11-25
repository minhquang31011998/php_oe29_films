<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Movie;
use App\Models\Playlist;
use App\Models\Tag;
use App\Models\Type;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdmin');
    }
    public function home()
    {
        $totalMovie = Movie::all()->count();
        $topMovies = Movie::orderByRaw('rate DESC')
            ->take(config('config.take_movie'))
            ->get();

        $totalPlaylist = Playlist::all()->count();
        $totalType = Type::all()->count();
        $totalTag = Tag::all()->count();

        $users = User::orderByRaw('updated_at DESC')
            ->where('role', config('config.role_user'))
            ->take(config('config.take_user'))
            ->get();

        return view('backend.dashboard')->with([
            'totalMovie' => $totalMovie,
            'totalPlaylist' => $totalPlaylist,
            'totalType' => $totalType,
            'totalTag' => $totalTag,
            'topMovies' => $topMovies->toArray(),
            'users' => $users,
        ]);
    }
}
