<?php

use App\Http\Resources\Backend\MediaResource;
use App\Models\Media;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

function filterString($filtername)
{
    if (request('sort') == 'asc') {
        return $filtername.'&sort=desc';
    }

    return $filtername.'&sort=asc';
}

function limitWord($str, $limit)
{
    $word = Str::words($str, $limit, '...');

    return $word;
}

function getImage($image, $alt = false)
{
    if ($image) {
        if(is_string($image)){
            $image = json_decode($image)[0] ?? null;
        }
        if(is_array($image)){
            $image = $image[0] ?? null;
            $image = json_decode(json_encode($image));
        }
        $url = $image ? env('APP_ASSET_URL').$image->path : null;
        if($alt){
            if(isset($image->altimage)){
                return ['url' =>  $url, 'alt' => $image->altimage];
            }
        }
        return $url;
    }

    return null;
}

function getAltImage($image)
{
    if ($image) {
        if(is_string($image)){
            $image = json_decode($image)[0] ?? null;
        }
        if(is_array($image)){
            $image = $image[0] ?? null;
            $image = json_decode(json_encode($image));
        }
        if($image){
            if(isset($image->altimage)){
                return  $image->altimage;
            }
        }
        return '';
    }

    return '';
}
function getExt($image)
{
    if ($image) {
        if(is_string($image)){
            $image = json_decode($image)[0] ?? null;
        }
        if(is_array($image)){
            $image = $image[0] ?? null;
            $image = json_decode(json_encode($image));
        }
        $image = $image ? $image->extension : null;

        return $image;
    }

    return null;
}

function currentUrl($number)
{
    $currentpage = request()->segment($number);

    return $currentpage;
}
function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
}

function readCSV($csvFile, $array)
{
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle)) {
        $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
    }
    fclose($file_handle);

    return $line_of_text;
}

function toTitle($str)
{
    return ucfirst(str_replace('-', ' ', $str));
}

function type()
{
    $type = request('type');
    $arrTypes = pageTypes();

    if (is_null($type)) {
        return false;
    }
    if (in_array($type, $arrTypes)) {
        return $type;
    }

    return abort(404);
}

function checkMeta($data){

    $title = env('META_TITLE');
    $desc =  env('META_DESCTIPTION');
    $image = env('META_IMAGE',url('/images/meta.jpg'));
    return [
        'title' => $data->title ?? $title,
        'description' => $data->description ?? $desc,
        'image' => is_null($data) ?  $image : getImage($data->image) ,
    ];
}
function jdecode($data)
{
    $res = [];
    foreach ($data as $key => $value) {
        $res[$key] = is_string($value) ? json_decode($value) : $value;
    }

    return $res;
}

function pageTypes()
{
    return [
        'menu', 'home', 'blog', 'news', 'article', 'static',
        'component', 'main-menu','footer-menu'
    ];
}

function pageTemplates()
{
    return [
        [
            'id' => 'home',
            'title' => 'Home',
        ],
        [
            'id' => 'wall-of-celebration',
            'title' => 'Wall of Celebration',
        ],
        [
            'id' => 'leaderboard',
            'title' => 'Leaderboard',
        ],
    ];
}

function vueFormExist($name, $folder, $default = 'form')
{
    $file = base_path().'/resources/js/Admin/Views/'.$folder.'/'.$name.'.vue';
    if (!file_exists($file)) {
        return $folder.'/'.$default;
    }

    return $folder.'/'.$name;
}

function templateExists($name)
{
    $file = base_path().'/resources/js/Frontend/Views/Pages/page-'.$name.'.vue';
    if (!file_exists($file)) {
        return 'Views/Pages/page-default';
    }

    return 'Views/Pages/page-'.$name;
}

function truncateText($text, $limit = 300) {
    // Hilangkan tag HTML
    $text = strip_tags($text);

    // Jika panjang teks melebihi batas, potong hingga batas yang ditentukan
    if (strlen($text) > $limit) {
        $text = substr($text, 0, $limit) . '...';
    }

    return $text;
}

function uploadLocal($request, $folder = 'user', $fromuri = false)
{
    if ($fromuri) {
        $files = [$request]; // Make sure it's an array
    } else {
        $files = is_array($request->file) ? $request->file : [$request->file]; // Make sure it's an array
    }

    $medias = [];
    foreach ($files as $key => $file) {
        // If the input is an image URL
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $response = Http::get($file);

            // Ensure the URL returned valid data
            if ($response->successful()) {
                $imageContent = $response->body();
                $fileName = basename(parse_url($file, PHP_URL_PATH)); // Get the image filename from URL
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileSize = strlen($imageContent); // Size in bytes

                // Ensure the temp directory exists
                $tempDir = storage_path('app/public/temp');
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0777, true); // Create temp directory if it doesn't exist
                }

                // Create a temporary file path (concatenate directory and file name)
                $tempFilePath = $tempDir . '/' . $fileName;

                // Write image content to the temporary file
                file_put_contents($tempFilePath, $imageContent);

                // Create an UploadedFile object
                $fileObject = new \Illuminate\Http\UploadedFile($tempFilePath, $fileName, $extension, null, true);
            } else {
                throw new Exception('Failed to download image from URL: ' . $file);
            }
        } else {
            // If it's a regular uploaded file
            $fileObject = $file;
            $fileName = $request->title ?? $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
        }

        $date = Carbon::now()->format('dmY-his');

        if (!is_dir(storage_path('/app/public/uploads/' . $folder))) {
            $folder_full = storage_path('/app/public/uploads/' . $folder);
            if (!is_dir($folder_full)) {
                mkdir($folder_full, 0777, true);
            }
        }
        $path = storage_path('/app/public/uploads/' . $folder);

        $download = $folder . '/';
        $media = new Media();
        $media->user_id = auth()->user()->id ?? null;
        $media->extension = $extension;
        $media->folder = $folder;
        $media->name = str_replace($extension, '', $fileName);
        $media->size = $fileSize;
        $media->filename = $fileName;
        $media->slug = $fileName;
        $media->save();

        $destinationPath = $path;
        $original = $media->slug . '-' . $date . '.' . $media->extension;
        $fileObject->move($destinationPath, $original);
        Cache::tags(['medias'])->flush();

        $filePath = '/uploads/' . $download . $original;
        $media->filename = $original;
        $media->path = $filePath;
        $media->url = env('ASSET_URL') . $filePath;
        $media->update();
        $medias[] = MediaResource::make($media)->resolve();

        // Delete the temporary file if created
        if (isset($tempFilePath) && file_exists($tempFilePath)) {
            unlink($tempFilePath);
        }
    }

    $return['status'] = true;
    $return['data'] = $medias;
    $return['message'] = 'success';

    return $return;

    try {
    } catch (Exception $e) {
        $return['status'] = false;
        $return['data'] = [];
        $return['message'] = $e->getMessage();

        return $return;
    }
}
