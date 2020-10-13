<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Requests\TypeRequest;

class TypeController extends Controller
{
    public function index()
    {
        return view('backend.types.index');
    }

    public function getData(Request $request)
    {
        $types = Type::select('id', 'title', 'description', 'created_at');
        if ($request->has('title')) {
            $types = $types->where('title', 'like', "%" . $request->get('title') . "%");
        }

        if ($request->has('sort')) {
            if ($request->get('sort') == trans('title')) {
                $types = $types->orderByRaw('title ASC');
            } elseif ($request->get('sort') == trans('date_created')) {
                $types = $types->orderByRaw('created_at DESC');
            }
        }

        return DataTables::of($types->get()->toArray())
            ->editColumn('id', function ($type) {
                return '<div class="main__table-text">' . $type['id'] . '</div>';
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
                        <form action="' . route('backend.type.destroy', $type['id']) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="main__table-btn main__table-btn--delete">
                                <i class="icon ion-ios-trash"></i>
                            </button>
                        </form>
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
        $type = new Type();
        $type->title = $request->get('title');
        $type->description = $request->get('description');
        $type->slug = Str::slug($request->get('title'));
        $type->save();

        return redirect()->route('backend.type.index');
    }

    public function edit($typeId)
    {
        try {
            $type = Type::findOrFail($typeId);

            return view('backend.types.edit')->with([
                'type' => $type->toArray(),
            ]);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function update(TypeRequest $request, $typeId)
    {
        try {
            $type = Type::findOrFail($typeId);
            $type->title = $request->get('title');
            $type->description = $request->get('description');
            $type->slug = Str::slug($request->get('title'));
            $type->save();

            return redirect()->route('backend.type.index');
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function destroy($typeId)
    {
        try {
            $type = Type::findOrFail($typeId)->movies()->detach();
            $type = Type::destroy($typeId);

            return redirect()->route('backend.type.index');
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }
}
