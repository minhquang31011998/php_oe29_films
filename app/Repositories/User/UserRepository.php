<?php
namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function getUsers($request)
    {
        $users = User::select('id', 'name', 'email', 'is_active', 'created_at')
            ->where('role', config('config.role_user'));
        if ($request->has('name')) {
            $users = $users->where('name', 'like', "%" . $request->get('name') . "%");
        }
        switch ($request->get('sort')) {
            case trans('name'):
                $users = $users->orderByRaw('name ASC');
                break;
            case trans('active'):
                $users = $users->where('is_active', '=', config('config.status_active'));
                break;
            case trans('hidden'):
                $users = $users->where('is_active', '=', config('config.status_hidden'));
                break;
            default:
                $users = $users->orderByRaw('created_at DESC');
                break;
        }

        $users = $users->get();

        foreach ($users as $index => $user) {
            $user->index = $index + 1;
        }

        return $users;
    }

    public function changeStatus($userId)
    {
        try {
            $user = User::findOrFail($userId);
            if ($user->is_active == config('config.status_active')) {
                $user->is_active = config('config.status_hidden');
            } else {
                $user->is_active = config('config.status_active');
            }
            $user->save();

            return $user;
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function changePassword($userId, $request)
    {
        $user = User::findOrFail($userId);
        $user->password = bcrypt($request->get('password'));

        return $user->save();
    }
}