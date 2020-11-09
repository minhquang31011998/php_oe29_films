<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TypeRequest;
use App\Repositories\Type\TypeRepositoryInterface;

class TypeController extends Controller
{
    protected $type;

    public function __construct(TypeRepositoryInterface $type)
    {
        $this->type = $type;
    }

    public function index()
    {
        return view('backend.types.index');
    }

    public function getData(Request $request)
    {
        $types = $this->type->getTypes($request);

        return DataTables::of($types->toArray())
        ->editColumn('id', function ($type) {
            return '<div class="main__table-text">' . $type['index'] . '</div>';
        })
        ->editColumn('title', function ($type) {
            return '<div class="main__table-text">' . $type['title'] . '</div>';
        })
        ->addColumn('action', function ($type) {
            return
                '<div class="main__table-btns">
                    <a href="' . route('backend.type.edit', $type['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                        <i class="icon ion-ios-create"></i>
                    </a>
                </div>';
        })
        ->rawColumns(['id', 'title', 'action'])
        ->make(true);
    }

    public function create()
    {
        return view('backend.types.create');
    }

    public function store(TypeRequest $request)
    {
        $this->type->storeType($request);

        alert()->success(trans('created'), trans('success'));

        return redirect()->route('backend.type.index');
    }

    public function show($typeId)
    {
        return $this->type->find($typeId);
    }

    public function edit($typeId)
    {
        $type = $this->type->find($typeId);

        return view('backend.types.edit')->with([
            'type' => $type->toArray(),
        ]);
    }

    public function update(TypeRequest $request, $typeId)
    {
        $this->type->updateType($request, $typeId);
        alert()->success(trans('updated'), trans('success'));

        return redirect()->route('backend.type.index');
    }

    public function destroy($typeId)
    {
        $this->type->deleteType($typeId);
        alert()->success(trans('deleted'), trans('success'));

        return redirect()->route('backend.type.index');
    }
}
