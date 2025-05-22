<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        
        $resources = ['Admin', 'Page', 'User','Media','Product','Product Category','Article','Tag'];
        $actions = ['View', 'Create', 'Edit', 'Delete'];

        foreach ($resources as $res) {
            foreach ($actions as $act) {
                Permission::firstOrCreate(['name' => "$act $res", 'guard_name' => 'admin']);
            }
        }
        $permissions = Permission::all();
        // Buat role Admin dan berikan semua permission
        $adminRole = Role::firstOrCreate(['name' => 'Master', 'guard_name' => 'admin']);
        $adminRole->syncPermissions($permissions);
       

        // Filter permission untuk editor
        $editorResourceFilters = ['Page', 'Product', 'Media', 'Product Category', 'Article', 'Tag'];

        $editorPermissions = Permission::query()
            ->where(function ($query) use ($editorResourceFilters) {
                foreach ($editorResourceFilters as $resource) {
                    $query->orWhere('name', 'like', "%$resource%");
                }
            })
            ->get();

        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'admin']);
        $editorRole->syncPermissions($editorPermissions);


        $admin = Admin::firstOrCreate(
            [
                'name' => 'Super Admin',
                'email' => 'super@mypage.id',
            ]
        );
        $admin->password = 'Super1q2w3e++2025';
        $admin->email_verified_at = Carbon::now();
        $admin->update();
        $admin->assignRole($adminRole);

        $this->command->info('Creating...'.$admin->name);


        $editor = Admin::firstOrCreate(
            [
                'name' => 'Editor',
                'email' => 'editor@mypage.id',
            ]
        );
        $editor->password = 'Super1q2w3e++2025';
        $editor->email_verified_at = Carbon::now();
        $editor->update();
        $editor->assignRole($editorRole);

        $this->command->info('Creating...'.$editor->name);
        cache()->flush();
    }
}
