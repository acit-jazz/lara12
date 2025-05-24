<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserPasswordRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\Backend\UserResource;
use App\Models\User;
use Facades\App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {

        $users = UserRepository::paginate(20);

        return Inertia::render('user/index', [
            'users' => UserResource::collection($users),
            'title' => request('trash') ? 'Trash' : 'User',
            'trash' => request('trash') ? true : false,
            'request' => request()->all(),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => 'User',
                    'url' => route('user.index'),
                ],
            ],
        ]);
    }

    /**
     * create view.
     */
    public function create()
    {
        $user = new User();
        $user = UserResource::make($user)->resolve();
        // dd($tags);

        return Inertia::render('user/create', [
            'user' => $user,
            'method' => 'post',
            'title' => 'Create '.'User',
            'locale' => app()->getLocale(),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => 'User',
                    'url' => route('user.index'),
                ],
            ],
        ]);
    }

    /**
     * store data.
     */
    public function store(UserRequest $request)
    {

        $user = User::create($request->all());

        Cache::tags(['users'])->flush();

        return redirect()->back()->with('message', toTitle($user->title).' has been updated');
    }

    /**
     * Edit view.
     */
    public function edit(User $user)
    {

        return Inertia::render('user/edit', [
            'user' => UserResource::make($user)->resolve(),
            'method' => 'patch',
            'title' => 'Edit '.'User',
            'locale' => app()->getLocale(),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => 'User',
                    'url' => route('user.index'),
                ],
                [
                    'text' => $user->name,
                    'url' => '',
                ],
            ],
        ]);
    }

    
    /**
     * Update Data.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->except(['password']));


        Cache::tags(['users'])->flush();

        return redirect()->back()->with('message', toTitle($user->title).' has been updated');
    }


    /**
     * Edit password view.
     */
    public function editPassword(User $user)
    {

        return Inertia::render('user/password', [
            'user' => UserResource::make($user)->resolve(),
            'method' => 'patch',
            'title' => 'Edit '.'User',
            'locale' => app()->getLocale(),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => 'User',
                    'url' => route('user.index'),
                ],
                [
                    'text' => $user->name,
                    'url' => '',
                ],
            ],
        ]);
    }
    /**
     * Update Password.
     */
    public function updatePassword(UserPasswordRequest $request, User $user)
    {

        $user->update([
            'password' => $request->password,
        ]);

        Cache::tags(['users'])->flush();

        return redirect()->back()->with('message', toTitle($user->title).' has been updated');
    }

    /**
     * Remove the specified resource from storage temporary.
     */
    public function delete($user)
    {
        $user = User::find($user);
        if (!$user) {
            return abort(404);
        }
        $user->delete();

        Cache::tags(['users'])->flush();

        return redirect()->route('user.index')->with('message', toTitle($user->title.' hase been deleted'));
    }

    /**
     * Remove data permanently.
     */
    public function destroy($user)
    {
        $user = User::withTrashed()->find($user);
        if (!$user) {
            return abort(404);
        }
        $user->forceDelete();

        Cache::tags(['users'])->flush();

        return redirect()->route('user.index')->with('message', toTitle($user->title.' hase been destroyed'));
    }

    public function destroyAll()
    {
        $ids = explode(',', request('selected'));
        $users = User::whereIn('_id', $ids)->withTrashed()->get();
        foreach ($users as $user) {
            $user->forceDelete();
        }
        Cache::tags(['users'])->flush();

        return redirect()->route('user.index')->with('message', toTitle($user->title).' has been destroyed');
    }

    /**
     * Restore Data from trash.
     */
    public function restore($user)
    {
        $user = User::withTrashed()->find($user);
        if (!$user) {
            return abort(404);
        }
        $user->restore();
        Cache::tags(['users'])->flush();

        return redirect()->route('user.index')->with('message', toTitle($user->title).' has been restored');
    }
}
