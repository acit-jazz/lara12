<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\PageResource;
use Facades\App\Http\Repositories\PageRepository;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug  = null)
    {
        $template = $slug ?? 'home';
        $page = PageRepository::findByTemplate($template);
        if(is_null($page)){
            return abort(404);
        }
        $meta = checkMeta($page->meta);
        $page = PageResource::make($page)->resolve();
        return Inertia::render('Views/Pages/Home', [
            'page' => $page,
         ])->withViewData([
            'meta' => $meta,
        ]);
    }

}
