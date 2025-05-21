<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
   // protected $rootView = 'app';

    public function rootView(Request $request): string
    {
        return str($request->path())->startsWith('backend') ? 'backend' : 'app';
    }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');
        $user = auth('admin')->user() ?? auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $user,
                'guard' => auth()->guard()->name
            ],
            'navigation' => navigation(),
            'components' => components(),
            'current_path'=> request()->fullUrl(),
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'has_flash' => $request->session()->get('message') ? true : false,
            ],
            'csrf_token' => csrf_token(),
            'env'=>[
                'app_name'=> env('APP_NAME'),
                'app_url'=> env('APP_URL'),
                'api_url'=> url('/api/v1'),
                'upload_url'=> env('APP_UPLOAD_URL'),
                'asset_url'=> env('APP_ASSET_URL'),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
