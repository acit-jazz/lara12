<?php

use App\Http\Resources\Frontend\NavigationResource;
use App\Models\Admin;
use Facades\App\Http\Repositories\PageRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

function navigation()
{
    $admin = auth('admin')->user();
    if ($admin && str(request()->path())->startsWith('backend')) {
        $navigatorCms = [
            'title' => env('APP_NAME'),
            'logo' => '/images/logo.svg',
            'link' => '/',
            'sections' => [
                [
                    'title' => 'Dashboard',
                    'href' => route('dashboard.index'),
                    'icon' => 'fa-table-columns',
                    'type' => 'menu',
                    'role_or_permission' => 'Master|View Dashboard',
                ],

                [
                    'title' => 'Pages',
                    'type' => 'header',
                    'role_or_permission' => 'Master|Editor|View Page',
                ],

                [
                    'title' => 'Static Pages',
                    'href' => route('page.index'),
                    'icon' => 'fa-file',
                    'type' => 'menu',
                    'role_or_permission' => 'Master|Editor|View Page',
                ],
            ],
        ];

        if (Route::has('product.index')) {
            $navigatorCms['sections'][] = [
                'title' => 'Product',
                'type' => 'header',
                'role_or_permission' => 'Master|Editor|View Product',
            ];
            $navigatorCms['sections'][] = [
                'title' => 'Product Catalog',
                'href' => route('product.index'),
                'icon' => 'fa-box',
                'type' => 'menu',
                'role_or_permission' => 'Master|Editor|View Product',
            ];
        }
        if (Route::has('product-category.index')) {
            $navigatorCms['sections'][] = [
                'title' => 'Product Category',
                'href' => route('product-category.index'),
                'icon' => 'fa-box',
                'type' => 'menu',
                'role_or_permission' => 'Master|Editor|View Product Category',
            ];
        }


        if (Route::has('article.index')) {
            $navigatorCms['sections'][] = [
                'title' => 'Article',
                'type' => 'header',
                'role_or_permission' => 'Master|Editor|View Article',
            ];
            $navigatorCms['sections'][] = [
                'title' => 'Article',
                'href' => route('article.index'),
                'icon' => 'fa-box',
                'type' => 'menu',
                'role_or_permission' => 'Master|Editor|View Article',
            ];
        }
        if (Route::has('tag.index')) {
            $navigatorCms['sections'][] = [
                'title' => 'Tag',
                'href' => route('tag.index'),
                'icon' => 'fa-box',
                'type' => 'menu',
                'role_or_permission' => 'Master|Editor|View Tag',
            ];
        }
        
        if (Route::has('administrator.index')) {
            $navigatorCms['sections'][] = [
                'title' => 'User',
                'type' => 'header',
                'role_or_permission' => 'Master|View User',
            ];
            $navigatorCms['sections'][] = [
                'title' => 'Administrator',
                'href' => route('administrator.index'),
                'icon' => 'fa-user-secret',
                'type' => 'menu',
                'role_or_permission' => 'Master|View User',
            ];
            $navigatorCms['sections'][] = [
                'title' => 'Member',
                'href' => route('user.index'),
                'icon' => 'fa-user',
                'type' => 'menu',
                'role_or_permission' => 'Master|View User',
            ];
        }

        $filterSections = function ($sections) use ($admin) {
            return array_filter($sections, function ($section) use ($admin) {
                if (! isset($section['role_or_permission'])) {
                    // Kalau tidak ada role_or_permission, tampilkan saja
                    return true;
                }

                $checks = explode('|', $section['role_or_permission']);

                foreach ($checks as $check) {
                    $check = trim($check);
                    if (empty($check)) {
                        continue;
                    }

                    // Cek apakah user punya role ini
                    if ($admin->hasRole($check)) {
                        return true;
                    }

                    // Cek apakah user punya permission ini dengan try-catch agar tidak error
                    try {
                        if ($admin->hasPermissionTo($check)) {
                            return true;
                        }
                    } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                        // Kalau permission tidak ada, lanjut cek lainnya
                    }
                }

                // Kalau tidak ada yang cocok, jangan tampilkan section ini
                return false;
            });
        };


        // Filter sections sesuai role atau permission
        $filteredSections = $filterSections($navigatorCms['sections']);
        $navigatorCms['sections'] = array_values($filteredSections);
        return $navigatorCms;
    }

}
