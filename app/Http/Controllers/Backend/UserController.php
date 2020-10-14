<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use App\Http\Requests\UserRequest;
use App\Http\Requests\PasswordRequest;

class UserController extends Controller
{
    public function index()
    {
        return view('backend.users.index');
    }

    public function getData(Request $request)
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

        return DataTables::of($users->get()->toArray())
            ->editColumn('id', function ($user) {
                return '<div class="main__table-text">' . $user['id'] . '</div>';
            })
            ->editColumn('name', function ($user) {
                return '<div class="main__table-text">' . $user['name'] . '</div>';
            })
            ->editColumn('email', function ($user) {
                return '<div class="main__table-text">' . $user['email'] . '</div>';
            })
            ->editColumn('is_active', function ($user) {
                $active = $user['is_active'] == config('config.status_active')
                    ?'<div class="main__table-text main__table-text--green">' . trans('active') . '</div>'
                    :'<div class="main__table-text main__table-text--red">' . trans('hidden') . '</div>';
                return $active;
            })

            ->addColumn('action', function ($user) {
                return
                    '<div class="main__table-btns">
                        <a href="' . route('backend.user.edit', $user['id']) . '" class="main__table-btn main__table-btn--edit open-modal" data-toggle="tooltip" title="Edit">
                                <i class="icon ion-ios-create"></i>
                            </a>
                        <form action="'. route('backend.user.destroy', $user['id']) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="main__table-btn main__table-btn--delete" data-toggle="tooltip" title="Delete">
                                <i class="icon ion-ios-trash"></i>
                            </button>
                        </form>
                    </div>';
            })
            ->rawColumns(['id','name', 'email', 'is_active','action'])
            ->make(true);
    }

    public function edit($userId)
    {
        try {
            $user = User::findOrFail($userId);

            return view('backend.users.edit')->with([
                'user' => $user->toArray(),
            ]);
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function update(UserRequest $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->name = $request->get('name');
            $user->phone = $request->get('phone');
            $user->address = $request->get('address');
            $user->save();

            return redirect()->route('backend.user.index');
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
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

    public function changePassword(PasswordRequest $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            if (!password_verify($request->get('oldPassword'), $user->password)) {
                Session::flash('status', trans('oldpass_wrong'));

                return redirect()->back();
            }
            $user->password = bcrypt($request->get('password'));
            $user->save();

            return redirect()->route('backend.user.index');
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function destroy($userId)
    {
        $user = User::destroy($userId);

        return redirect()->route('backend.user.index');
    }
}
