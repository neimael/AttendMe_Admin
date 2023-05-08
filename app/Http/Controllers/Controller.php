<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\URL;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    //save image
    public function saveImage($image, $path = 'public')
    {
        if(!$image)
        {
            return null;
        }

        $filename = time().'.png';
        // save image
        \Storage::disk($path)->put($filename, base64_decode($image));

        //return the path
        // Url is the base url exp: localhost:8000
        return URL::to('/').'/storage/'.$path.'/'.$filename;
    }

    // save file
    public function saveFile($file, $path = 'public', $extension)
    {
        if (!$file) {
            return null;
        }

        // $extension = $file->getClientOriginalExtension();
        $filename = time() .'.'. $extension;
        // save file
        \Storage::disk($path)->put($filename, base64_decode($file));

        // $file->storeAs($path, $filename);

        //return the path
        // Url is the base url exp: localhost:8000

        return URL::to('/').'/storage/'.$path.'/'.$filename;
    }

}
