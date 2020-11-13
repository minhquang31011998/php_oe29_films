<?php
namespace App\Repositories\Type;

use App\Models\Type;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TypeRepository extends BaseRepository implements TypeRepositoryInterface
{
    public function getModel()
    {
        return Type::class;
    }

    public function getTypes($request)
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
        $types = $types->get();
        foreach ($types as $index => $type) {
            $type->index = $index + 1;
        }

        return $types;
    }

    public function storeType($request)
    {
        $type = new Type();
        $type->title = $request->get('title');
        $type->description = $request->get('description');
        $type->slug = Str::slug($request->get('title'));

        return $type->save();
    }

    public function updateType($request, $typeId)
    {
        try {
            $type = Type::findOrFail($typeId);
            $type->title = $request->get('title');
            $type->description = $request->get('description');
            $type->slug = Str::slug($request->get('title'));

            return $type->save();
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function deleteType($typeId)
    {
        try {
            $type = Type::findOrFail($typeId)->movies()->detach();

            return Type::destroy($typeId);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function getCountOfMovieFromType()
    {
        $types = Type::withCount('movies')->get();

        if ($types == null) {
            return null;
        }
        foreach ($types as $type) {
            $data [] = [
                'name' => $type->title,
                'y' => $type->movies_count,
            ];
        }

        return $data;
    }
}
