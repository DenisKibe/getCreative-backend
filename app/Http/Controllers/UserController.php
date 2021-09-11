<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\UserExtraDetails;
use App\Http\Controllers\Controller;
use Storage;

//function to generate a unique 15 chars string
function genIdentifier(){
    $id=array();
    $characters=['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

    for($i=0;$i<15;$i++){
        array_push($id,$characters[array_rand($characters)]);
    }

    return implode($id);
}


class UserController extends Controller
{
    //assign the auth middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    //return the information of a user
    public function userInfo(Request $request)
    {
        $info=User::select('userId','name','email','user_role')->where('userId',$request->user()->userId)->first();
        $info=$info->toArray();
        $profilePic=UserExtraDetails::select('profile_picture')->where('user_id',$request->user()->userId)->first();
        $profilePic=$profilePic->toArray();
        $profilePic=$profilePic['profile_picture'];


        $info=array_add($info,'profilePicture',$profilePic);

        json_encode($info);

        return response($info, 200);

    }

    /**
     * update a profile picture for a user
     */
    public function uploadPic(Request $request)
    {
        $this->validate($request,[
            'profile_image'=>'required|image',
        ]);


        Storage::put('profilePictures/'.$request->user()->userId, file_get_contents($request->file('profile_image')->getRealPath()));
        $type=Storage::mimeType('profilePictures/'.$request->user()->userId);
        $start=strpos($type,'/')+1;
        $type=substr($type,$start,strlen($type));

        $exist=UserExtraDetails::select('profile_picture')->where('user_id',$request->user()->userId)->get();

        if(count($exist)>0)
        {
            $request->user()->userExtraDetails()->update([
                'profile_picture'=>'profilePictures/'.$request->user()->userId.'.'.$type,
            ]);

            return response()->json(['message'=>'upload successful'], 200);
        }
        $request->user()->userExtraDetails()->create([
           'details_id'=>genIdentifier(),
            'profile_picture'=>'profilePictures/'.$request->user()->userId.'.'.$type,
        ]);


        return response()->json(['message'=>'upload successful.'], 200);
    }

    /**
     * get the profile pic
     */
    public function getDp(Request $request)
    {
        $image=UserExtraDetails::select('profile_picture')->where('user_id',$request->user()->userId)->first();

        



        return response()->json(['url'=>'http://me.semewear.co.ke/storage/app/'.$image]);
    }


}
