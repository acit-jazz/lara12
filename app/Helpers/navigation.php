<?php

use App\Http\Resources\Frontend\NavigationResource;
use App\Models\Admin;
use Facades\App\Http\Repositories\PageRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

function navigation()
{
    $user = auth('admin')->user() ?? auth()->user();
    if (!$user) {
       return abort(403, 'Unauthorized');
    }
    if ($user && str(request()->path())->startsWith('backend')) {
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
                ],

                [
                    'title' => 'Pages',
                    'type' => 'header',
                ],

                [
                    'title' => 'Static Pages',
                    'href' => route('page.index'),
                    'icon' => 'fa-file',
                    'type' => 'menu',
                ],
            ],
        ];


        if (Route::has('product.index')) {
            $navigatorCms['sections'][] = [
                'title' => 'Product',
                'type' => 'header',
            ];
            $navigatorCms['sections'][] = [
                'title' => 'Product Catalog',
                'href' => route('product.index'),
                'icon' => 'fa-box',
                'type' => 'menu',
            ];
        }
        if (Route::has('product-category.index')) {
            $navigatorCms['sections'][] = [
                'title' => 'Product Category',
                'href' => route('product-category.index'),
                'icon' => 'fa-box',
                'type' => 'menu',
            ];
        }
        if (Route::has('administrator.index')) {
            $navigatorCms['sections'][] = [
                'title' => 'User',
                'type' => 'header',
            ];
            $navigatorCms['sections'][] = [
                'title' => 'Administrator',
                'href' => route('administrator.index'),
                'icon' => 'fa-user-secret',
                'type' => 'menu',
            ];
            $navigatorCms['sections'][] = [
                'title' => 'Member',
                'href' => route('user.index'),
                'icon' => 'fa-user',
                'type' => 'menu',
            ];
        }

        return $navigatorCms;
    }

}
