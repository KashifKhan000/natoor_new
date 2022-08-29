<?PHP

use App\Models\LanguagesContents;
use App\Models\MediaFiles;
use App\Models\User;
use App\Models\UserLog;

function checkType(){
    if(auth()->user()->type > 0){
        return false;
    }else{
        return true;
    }
}

function saveLanguage($lang_code = "",$name = "",$id = "",$type = ""){
    $language = new LanguagesContents();
    $language->item_id = $id;
    $language->item_type = $type;
    $language->lang_code = $lang_code;
    $language->content_title = $name;
    $language->save();
}

function updateLanguage($lang_code = "",$name="",$id="",$type=""){
    $language = LanguagesContents::where(['item_id' => $id,'item_type' => $type,'lang_code' => $lang_code])->first();
    $language->content_title = $name;
    $language->lang_code = $lang_code;
    $language->save();
}

function saveImages($images_url = [],$id,$type){
    $images_url = is_array($images_url) ? $images_url : [$images_url];
    if(!empty($images_url)){
        foreach($images_url as $image_url){
            $media = new MediaFiles();
            $media->item_id = $id;
            $media->item_type = $type;
            $media->file_path = $image_url;
            $media->save();
        }
    }
}

function updateImages($images_url = [],$id,$type){
    $images_url = is_array($images_url) ? $images_url : [$images_url];
    if(!empty($images_url)){
        MediaFiles::where(['item_id'=>$id,'item_type' => $type])->delete();
        foreach($images_url as $image_url){
            $media = new MediaFiles();
            $media->item_id = $id;
            $media->item_type = $type;
            $media->file_path = $image_url;
            $media->save();
        }

    }

    function storeUserHistoryInfo($user_id = "", $key = "",$value=""){
        $userLog = new UserLog();
        $userLog->user_id = $user_id;
        $userLog->key = $key;
        $userLog->value = $value;
        $userLog->save();
    }

}

?>
