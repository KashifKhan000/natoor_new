<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoiceUploadController extends Controller
{
    public function uploadVoice(Request $request){
        if(!empty($request->voice)){
            $voice_url = [];
            $a=0;
            foreach($request->file('voice') as $voice){
                $fileName = time().$a++.'.'.$voice->extension();
                $voice->move(public_path('uploads/voice/'), $fileName);
                array_push($voice_url,url('uploads/voice/'.$fileName));
            }
            return $voice_url;
        }
    }
}
