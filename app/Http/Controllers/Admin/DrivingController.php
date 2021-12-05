<?php

namespace App\Http\Controllers\Admin;

use App\DrivingMania;
use App\DrivingMedia;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DrivingController extends Controller
{
    public function createModule(Request $request){
        try {
            $driving = new DrivingMania();
            $driving->title = $request->title;
            $driving->description = $request->description;
            $driving->title = $request->title;
            $user = User::find(auth()->user()->id);
            $user->driving_mania()->save($driving);
            $media = new DrivingMedia();
            $mediaArray = [];
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $item){
                    $videoTypes = ['mp4', '3GP', 'OGG', 'WMV', 'WEBM', 'FLV', 'AVI', 'QuickTime', 'HDV', 'MXF', 'MPEG-TS', 'MPEG-2', 'WAV', 'MPEG4'];
                    $imagesTypes = ['jpg', 'jpeg', 'png'];
                    $extension = $item->getClientOriginalExtension();
                    $name = $item->getClientOriginalName().$extension;
                    if (in_array($extension, $videoTypes)) {
                    $extension = 'Video';
                    }
                    if (in_array($extension, $imagesTypes)) {
                        $extension = 'Image';
                    }
                    $file = time() . '-' . $item->getClientOriginalName();
                    $destination = public_path("Module/media/$driving->id");
                    $path = $item->move($destination, $file);
                    $mediaArray[] = new DrivingMedia(
                    [
                        'media_link' => $path,
                        'media_type' => $extension,
                        'file_name' => $name
                    ]
                    );
                }
            }
            $driving->media()->saveMany($mediaArray);
            return response()->json(['Message' => 'Module Created Successfully'], 200);
        }catch (Exception $e){
            return response()->json(['Message' => $e->getMessage()], 404);
        }
    }
}
