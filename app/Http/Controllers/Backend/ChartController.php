<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Type\TypeRepositoryInterface;

class ChartController extends Controller
{
    protected $type;

    public function __construct(TypeRepositoryInterface $type)
    {
        $this->type = $type;
    }

    public function index()
    {
        $types = $this->type->getCountOfMovieFromType();

        return view('backend.chart')->with([
            'types' => json_encode($types),
        ]);
    }
}
