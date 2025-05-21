<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\AdministratorRequest;
use App\Http\Requests\Administrator\AdministratorUpdatePasswordRequest;
use App\Http\Requests\Administrator\AdministratorUpdateRequest;
use App\Http\Resources\Backend\AdministratorResource;
use App\Models\Admin;
use App\Models\AdminPassword;
use Carbon\Carbon;
use Facades\App\Http\Repositories\AdministratorRepository;
use Facades\App\Http\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminstratorController extends Controller
{
    public function index()
    {
        $adminstrators = AdministratorRepository::paginate(20);

        return Inertia::render('administrator/index', [
            'administrators' => AdministratorResource::collection($adminstrators),
            'title' => request('trash') ? 'Trash' : 'Administrator',
            'trash' => request('trash') ? true : false,
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => '/backend',
                ],
                [
                    'text' => 'Administrators',
                    'url' => '/backend/administrator',
                ],
            ],
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function create(Request $request)
    {
        $adminstrator = [
            'name' => '',
            'roles' => 'admin',
            'email' => '',
            'password' => '',
            'repassword' => '',
        ];
        // dd($role);

        return Inertia::render('administrator/create', [
            'title' =>  'Create New Administrator',
            'status' => session('status'),
            'administrator' => $adminstrator,
            'method' => 'store',
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function store(AdministratorRequest $request)
    {
            $admin = Admin::create($request->all());
            $admin->adminPassword()->create(['password' => Hash::make($request->password)]);

            Cache::tags('administrator')->flush();

            return redirect('backend/administrator')->with('message', 'proses menambah  administrator berhasil');
    }

    /**
     * Display the user's profile form.
     */
    public function editPassword(Admin $administrator)
    {
        $start = Carbon::now()->subMinutes(env('PASSWORD_EXPIRED', 60 * 24 * 30 * 3));
        $lastPass = AdminPassword::where('admin_id', $administrator->id)->latest('created_at')->first();
        $message = '';
        if (!is_null($lastPass)) {
            if ($lastPass->created_at < $start) {
                $message = 'Your password has not been changed for 3 months, please change your password';
            }
        }

        return Inertia::render('administrator/password', [
            'title' => 'Change Password ',
            'administrator' => $administrator,
            'message' => $message,
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => '/backend',
                ],
                [
                    'text' => 'Administrators',
                    'url' => '/backend/administrator',
                ],
                [
                    'text' => $administrator->name,
                    'url' => '',
                ],
            ],
        ]);
    }

    public function updatePassword(AdministratorUpdatePasswordRequest $request)
    {
        $administrator = Admin::find($request->id);

        $administrator->password = Hash::make($request->password);
        $administrator->update();
        $administrator->adminPassword()->create(['password' => Hash::make($request->password)]);
        Cache::tags('administrator')->flush();

        return redirect('backend/administrator')->with('message', 'proses update  administrator berhasil');
    }

    public function edit(Admin $administrator)
    {
        $administrator = $administrator;

        return Inertia::render('administrator/edit', [
             'administrator' => $administrator,
             'breadcumb' => [
                 [
                     'text' => 'Dashboard',
                     'url' => '/backend',
                 ],
                 [
                     'text' => 'Administrators',
                     'url' => '/backend/administrator',
                 ],
                 [
                     'text' => $administrator->name,
                     'url' => '',
                 ],
             ],
         ]);
    }

    /**
     * Display the user's profile form.
     */
    public function update(AdministratorUpdateRequest $request)
    {
        try {
            $post = $request->all();

            $administrator = Admin::where(
                'id',
                $post['id']
            )->first();
            // dd($administrator->name);
            $administrator->name = $post['name'];
            // $administrator->bod = $post['bod'];
            $administrator->email = $post['email'];
            if ($post['password'] != '') {
                $administrator->password = Hash::make($post['password']);
            }

            $administrator->save();
            Cache::tags('administrator')->flush();

            return redirect('backend/administrator')->with('message', 'proses update  administrator berhasil');
        } catch (\Throwable $th) {
            dd($th);

            return redirect('backend/administrator')->with('message', 'mohon maaf sedang ada gangguan silahkan coba bebrapa saat lagi');
        }
    }

    /**
     * Remove the specified resource from storage temporary.
     */
    public function delete($administrator)
    {
        $administrator = Admin::find($administrator);
        $administrator->delete();

        Cache::tags(['administrator'])->flush();

        return redirect('backend/administrator')->with('message', toTitle($administrator->name.' hase been deleted'));
    }

    /**
     * Remove data permanently.
     */
    public function destroy($administrator)
    {
        $administrator = Admin::withTrashed()->find($administrator);
        $administrator->forceDelete();

        Cache::tags(['administrator'])->flush();

        // return redirect('backend/administrator')->with('message', toTitle($administrator->name.' hase been destroyed'));
        return redirect()->route('administrator.index', ['trash' => '1'])->with('message', toTitle($administrator->name.' hase been destroyed'));
    }

    public function destroyAll()
    {
        $ids = explode(',', request('selected'));
        $administrators = Admin::whereIn('id', $ids)->withTrashed()->get();
        foreach ($administrators as $administrator) {
            $administrator->forceDelete();
        }
        Cache::tags(['administrator'])->flush();

        return redirect()->route('administrator.index', ['trash' => '1'])->with('message', toTitle($administrator->name).' has been destroyed');
    }

    /**
     * Restore Data from trash.
     */
    public function restore($administrator)
    {
        $administrator = Admin::withTrashed()->find($administrator);
        $administrator->restore();
        Cache::tags(['administrator'])->flush();

        return redirect()->route('administrator.index', ['trash' => '1'])->with('message', toTitle($administrator->name).' has been restored');
    }
}
