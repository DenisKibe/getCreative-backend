<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requests;
use App\Http\Controllers\Controller;
use App\Followers;
use App\User;
use App\UserExtraDetails;


//function to generate a unique 15 chars string
function genIdentifier(){
    $id=array();
    $characters=['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

    for($i=0;$i<15;$i++){
        array_push($id,$characters[array_rand($characters)]);
    }

    return implode($id);
}

class FollowersController extends Controller
{
    //assign the auth middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    //return the count of followers i have followed
    public function followed(Request $request)
    {


        $followers=Followers::select('follow')->where('user_id',$request->user()->userId)->count();

        return response()->json(['count'=>$followers]);
    }

    //create a new follow
    public function follow(Request $request)
    {
        $this->validate($request,[
            'user_id'=>'required',
        ]);

        //validate a user has not follower this user before
        $check=Followers::select('follow_id')->where([
            ['user_id',$request->user()->userId],
            ['followed',$request->user_id],
        ])->get();

        if(count($check)>0)
        {
            //delete the follow
            Followers::where([
                ['user_id',$request->user()->userId],
                ['followed',$request->user_id],
            ])->delete();
            return response()->json([],200);
        }else{
            //create the follower
            $request->user()->followers()->create([
                'follow_id'=>genIdentifier(),
                'followed'=>$request->user_id,
                'follow'=>'1',
            ]);
            return response()->json([],201);
        }
    }

    //return the users who i have followered
    public function userFollowed(Request $request)
    {
        $followers=Followers::select('followed')->where('user_id',$request->user()->userId)->get();

        $finalResponse=array();


        if(count($followers)>0)
        {

            for($i=0;$i<count($followers);$i++)
            {
                $follower=$followers[$i];
                $follower=$follower->toArray();
                $follower=$follower['followed'];
                $name=User::select('name')->where('userId',$follower)->first();
                $name=$name->toArray();
                $name=$name['name'];
                $profilepic=UserExtraDetails::select('profile_picture')->where('user_id',$follower)->get();
                if(count($profilepic)==0)
                {
                    $newResponse=json_encode(['name'=>$name,'profilepic'=>'null']);
                }else{


                    $profilepic=$profilepic[0]->toArray();
                    $profilepic=$profilepic['profile_picture'];

                    $newResponse=json_encode(['name'=>$name,'profilepic'=>$profilepic]);


                }

                //push the name to the final array
                array_push($finalResponse,$newResponse);

            }

            return response(json_encode($finalResponse),200);
        }

        return response(json_encode($finalResponse),200);
    }

    //return the count of followers who follow me
    public function followers(Request $request)
    {


        $followers=Followers::select('follow')->where('followed',$request->user()->userId)->count();

        return response()->json(['count'=>$followers],200);
    }

    //return the users who  follow me
    public function userFollowers(Request $request)
    {
        $followers=Followers::select('user_id')->where('followed',$request->user()->userId)->get();

        $finalResponse=array();


        if(count($followers)>0)
        {

            for($i=0;$i<count($followers);$i++)
            {
                $follower=$followers[$i];
                $follower=$follower->toArray();
                $follower=$follower['followed'];
                $name=User::select('name')->where('userId',$follower)->first();
                $name=$name->toArray();
                $name=$name['name'];
                $profilepic=UserExtraDetails::select('profile_picture')->where('user_id',$follower)->get();
                if(count($profilepic)==0)
                {
                    $newResponse=json_encode(['name'=>$name,'profilepic'=>'null']);
                }else{


                    $profilepic=$profilepic[0]->toArray();
                    $profilepic=$profilepic['profile_picture'];

                    $newResponse=json_encode(['name'=>$name,'profilepic'=>$profilepic]);


                }

                //push the name to the final array
                array_push($finalResponse,$newResponse);

            }

            return response(json_encode($finalResponse),200);
        }

        return response(json_encode($finalResponse),200);
    }
}
