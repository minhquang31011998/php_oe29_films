<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use App\Http\Requests\SourceRequest;
use App\Repositories\Source\SourceRepositoryInterface;

class SourceController extends Controller
{
    protected $source;

    public function __construct(SourceRepositoryInterface $source)
    {
        $this->source = $source;
    }

    public function store(SourceRequest $request)
    {
        Session::flash('status', 'new');

        return $this->source->storeSource($request);
    }

    public function edit($sourceId)
    {
        $source = $this->source->find($sourceId);

        return response()->json([
            'source'  => $source,
        ]);
    }

    public function update(SourceRequest $request, $sourceId)
    {
        return $source = $this->source->updateSource($request, $sourceId);
    }

    public function destroy($sourceId)
    {
        return $source = $this->source->deleteSource($sourceId);
    }
}
