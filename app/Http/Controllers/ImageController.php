<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    protected $s3;

    public function __construct(S3Client $s3)
    {
        $this->s3 = $s3;
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        
        $file = $request->file('image');

        $imageName = uniqid('image_').'.' . $file->getClientOriginalExtension();

        $path = 'images/' . $imageName;

        // Use Laravel's Storage facade to upload the file to S3
        Storage::disk('s3')->put($path, file_get_contents($file), 'public');

        // Get the public URL of the uploaded file from S3
        $url = $this->s3->getObjectUrl(env('AWS_BUCKET'), $path);

        return response()->json(['success' => true, 'url' => $url, 'path' => $path], 201);
    }

    public function deleteImages(Request $request){
        // $request에서 'deleteList' 이름으로 보낸 JSON 문자열을 가져와서 배열로 변환
        $deleteList = json_decode($request->input('deleteList'), true);
    
        $deleted = Storage::disk('s3')->delete($deleteList);
    
        $msg = '';
    
        if ($deleted) {
            $msg = 'success';
        } else {
            $msg = 'failed';
        }
    
        return response()->json(['msg' => $msg]);
    }
    
}
