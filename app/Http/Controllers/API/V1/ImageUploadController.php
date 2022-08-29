<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request){
        if(!empty($request->images)){
            $images_url = [];
            $a=0;
            foreach($request->file('images') as $image){
                $fileName = time().$a++.'.'.$image->extension();
                $image->move(public_path('uploads/images/'), $fileName);
                array_push($images_url,url('uploads/images/'.$fileName));
            }
            $response = ['status' => 200, 'data' => $images_url];
            return response($response, 200);
        }
    }
}
