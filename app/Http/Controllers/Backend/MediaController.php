<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\MediaRequest;
use App\Http\Resources\Backend\MediaResource;
use App\Models\Media;
use Carbon\Carbon;
use Facades\App\Http\Repositories\MediaRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function index()
    {
        request()->merge([
            'user_id' => auth()->user()->id ?? null,
        ]);
        $medias = MediaRepository::paginate(20);
        return response()->json([
            'data' => MediaResource::collection($medias)->resolve(),
            'meta' => [
                'current_page' => $medias->currentPage(),
                'last_page' => $medias->lastPage(),
                'per_page' => $medias->perPage(),
                'total' => $medias->total(),
                'links' => [
                    'next' => $medias->nextPageUrl(),
                    'previous' => $medias->previousPageUrl(),
                ]
            ]
        ]);
    }

    /**
     * store data.
     */
    public function store(MediaRequest $request)
    {
        $date = Carbon::now()->format('dmY-his');

        $folder = Str::snake($request->folder);
        $image = $request->file('file');
        $uploads = uploadLocal($request, $folder);

        return $uploads;
        if ($uploads['status'] == true) {
            return response()->json(new MediaResource($uploads['data']));
        } else {
            return response()->json(false, 422);
        }
    }

    /**
     * Edit view.
     */
    public function edit(Media $media)
    {
        return Inertia::render('Views/'.vueFormExist(type(), 'media', 'form'), [
            'media' => $media,
            'type' => type(),
            'method' => 'update',
            'title' => 'Edit '.toTitle(type()),
            'breadcumb' => [
                [
                    'text' => 'Dashboard',
                    'url' => route('dashboard.index'),
                ],
                [
                    'text' => toTitle(type()),
                    'url' => route('post.index', ['type' => type()]),
                ],
            ],
        ]);
    }

    /**
     * Update Data.
     */
    public function update(MediaRequest $request, Media $media)
    {
        $media->update($request->all());
        Cache::tags(['medias'])->flush();

        return redirect()->route('post.index', ['type' => type()])->with('message', toTitle($media->title).' has been updated');
    }

    /**
     * Remove the specified resource from storage temporary.
     */
    public function delete($media)
    {
        $media = Media::find($media);
        if (!$media) {
            return abort(404);
        }
        $media->delete();

        Cache::tags(['medias'])->flush();

        return redirect()->route('post.index', ['type' => type()])->with('message', toTitle($media->title.' hase been deleted'));
    }

    /**
     * Remove data permanently.
     */
    public function destroy($media)
    {
        $media = Media::withTrashed()->find($media);
        if (!$media) {
            return response()->json(true, 200);
        }
        //   Storage::disk('local')->delete('uploads/'.$media->url);
        $media->forceDelete();

        Cache::tags(['medias'])->flush();

        return response()->json(true, 200);
    }

    public function destroyAll()
    {
        $ids = explode(',', request('selected'));
        $medias = Media::whereIn('_id', $ids)->withTrashed()->get();
        foreach ($medias as $media) {
            $media->forceDelete();
        }
        Cache::tags(['medias'])->flush();

        return redirect()->route('post.index', ['type' => type(), 'trash' => 1])->with('message', toTitle($media->title).' has been destroyed');
    }

    /**
     * Restore Data from trash.
     */
    public function restore($media)
    {
        $media = Media::withTrashed()->find($media);
        if (!$media) {
            return abort(404);
        }
        $media->restore();
        Cache::tags(['medias'])->flush();

        return redirect()->route('post.index', ['type' => type(), 'trash' => 1])->with('message', toTitle($media->title).' has been restored');
    }
}
