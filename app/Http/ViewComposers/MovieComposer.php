<?php
namespace App\Http\ViewComposers;

use App\Models\Option;
use App\Models\Type;
use Illuminate\View\View;

class MovieComposer
{
    public $mainMenu = [];

    public function __construct()
    {
        $this->mainMenu['types_movie'] = Type::select('title', 'slug')->get()->toArray();
        $genre = Option::where('name', config('config.default_name_genre'))->first();
        $country = Option::where('name', config('config.default_name_country'))->first();
        $this->mainMenu['country'] = $country->optionValues->toArray();
        $this->mainMenu['genre'] = $genre->optionValues->toArray();
    }

    public function compose(View $view)
    {
        $view->with('mainMenu', $this->mainMenu);
    }
}
