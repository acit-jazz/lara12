<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class UserManagementController extends Controller
{
    public function index()
    {
        Cache::tags(['users'])->flush();
        $permissionusers = PermissionUserRepository::paginate(20);
        // dd(app()->getLocale());
        // dd(TypePeopleResource::collection($typepeoples));
        // dd(vueFormExist('index', 'roleuser', 'index'));

        return Inertia::render('Views/'.vueFormExist('index-permissionuser', 'permissionuser', 'index'), [
            'permissionusers' => PermissionsUserResource::collection($permissionusers),
            'title' => request('trash') ? 'Trash' : 'Permission User',
            'locale' => app()->getLocale(),
            'trash' => request('trash') ? true : false,
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => 'Permission User',
                    'url' => route('permission-user.index', ['locale' => app()->getLocale()]),
                ],
            ],
        ]);
    }

    /**
     * create view.
     */
    public function create()
    {
        $permissionuser = new PermissionsUser();
        // dd($event);
        $permissionuser = PermissionsUserResource::make($permissionuser)->resolve();
        // dd($event);

        return Inertia::render('Views/'.vueFormExist('permissionuser', 'permissionuser', 'form'), [
            'permissionuser' => $permissionuser,
            'method' => 'store',
            'title' => 'Create Permisiion User',
            'locale' => app()->getLocale(),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => ' Permission User',
                    'url' => route('permission-user.index', ['locale' => app()->getLocale()]),
                ],
            ],
        ]);
    }

    /**
     * store data.
     */
    public function store(PermissionsUserRequest $request, PermissionsUser $permissionsUser)
    {
        $permissionsUser = PermissionsUser::create($request->all());

        Cache::tags(['permissionsUser'])->flush();

        return redirect()->route('permission-user.index')->with('message', toTitle($permissionsUser->name).' has been created');
    }

    /**
     * Edit view.
     */
    public function edit(PermissionsUser $permissionuser)
    {
        // dd($people);

        return Inertia::render('Views/'.vueFormExist('permissionuser', 'permissionuser', 'form'), [
            'permissionuser' => $permissionuser,
            'method' => 'update',
            'title' => 'Edit Permission User',
            'locale' => app()->getLocale(),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => '  Permission User',
                    'url' => route('permission-user.index', ['locale' => app()->getLocale()]),
                ],
            ],
        ]);
    }

    /**
     * Update Data.
     */
    public function update(PermissionsUserRequest $request, PermissionsUser $permissionuser)
    {
        $permissionuser->update($request->all());
        Cache::tags(['permissionuser'])->flush();

        return redirect()->route('permission-user.index')->with('message', toTitle($permissionuser->name).' has been updated');
    }

    /**
     * Remove the specified resource from storage temporary.
     */
    public function delete($permissionuser)
    {
        $permissionuser = PermissionsUser::find($permissionuser);
        if (!$permissionuser) {
            return abort(404);
        }
        $permissionuser->delete();

        Cache::tags(['permissionuser'])->flush();

        return redirect()->route('permission-user.index')->with('message', toTitle($permissionuser->name.' hase been deleted'));
    }

    /**
     * Remove data permanently.
     */
    public function destroy($permissionuser)
    {
        $permissionuser = PermissionsUser::withTrashed()->find($permissionuser);
        if (!$permissionuser) {
            return abort(404);
        }
        $permissionuser->forceDelete();

        Cache::tags(['permissionuser'])->flush();

        return redirect()->route('permission-user.index', ['trash' => 1])->with('message', toTitle($permissionuser->name.' hase been destroyed'));
    }

    public function destroyAll()
    {
        $ids = explode(',', request('selected'));
        $permissionusers = PermissionsUser::whereIn('_id', $ids)->withTrashed()->get();
        foreach ($permissionusers as $permissionuser) {
            $permissionuser->forceDelete();
        }
        Cache::tags(['permissionuser'])->flush();

        return redirect()->route('permission-user.index', ['trash' => 1])->with('message', toTitle($permissionuser->name).' has been destroyed');
    }

    /**
     * Restore Data from trash.
     */
    public function restore($permissionuser)
    {
        $permissionuser = PermissionsUser::withTrashed()->find($permissionuser);
        if (!$permissionuser) {
            return abort(404);
        }
        $permissionuser->restore();
        Cache::tags(['permissionuser'])->flush();

        return redirect()->route('permission-user.index', ['trash' => 1])->with('message', toTitle($permissionuser->name).' has been restored');
    }
}
