<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Likes;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UserExtraDetails;

class LikeController extends Controller
{
     /**
     * assign auth middleware
     */
    public function __construct()
    {
        $this->middleware('auth');


    }
    /**
     * return all the number of likes
     */
    public function index(Request $request)
    {
        $this->validate($request,[
            'article_id'=>'required',
        ]);

        $likes=Likes::select('likes')->where([
            ['article_id',$request->article_id],
            ['likes','1'],
            ])->count();

        return response()->json(['count'=>$likes]);
    }

    /**
     * create a new  likes
     */
    public function likeIt(Request $request)
    {
        //function to generate a unique 15 chars string
        function genIdentifier(){
            $id=array();
            $characters=['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

            for($i=0;$i<15;$i++){
	            array_push($id,$characters[array_rand($characters)]);
            }

            return implode($id);
        }
        $this->validate($request,[
            'article_id'=>'required',
        ]);
        //validate a user has not liked this article before

        $like=Likes::select('user_id','article_id')
            ->where('user_id',$request->user()->userId)
            ->where('article_id',$request->article_id)
            ->where('likes','1')
            ->get();
        $countLikes=count($like);

        if($countLikes>0)
        {
            //delete like if it exists
            Likes::where([
                ['article_id',$request->article_id],
                ['user_id',$request->user()->userId],
                ])->delete();
                
                return response()->json([],200);
        }else{
            //create the like
            $request->user()->likes()->create([
                'like_identifier'=>genIdentifier(),
                'article_id'=>$request->article_id,
                'likes'=>'1',
            ]);
            return response()->json([],201);
        }
    }

    //return a list of people who liked an article
    public function peopleLiked(Request $request)
    {
        $this->validate($request,[
            'article_identifier'=>'required',
        ]);

        $likers=Likes::select('user_id')->where('article_id',$request->article_identifier)->get();

        $finalResponse=array();

        if(count($likers)>0)
        {
            for($i=0;$i<count($likers);$i++)
            {
                $name=User::select('name')->where('userId',$likers[$i])->first();
                $name=$name->toArray();
                $name=$name['name'];
                $profilePic=UserExtraDetails::select('profile-picture')->where('userId',$likers[$i])->first();
                $profilePic=$profilePic->toArray();
                $profilePic=$profilePic['profile_picture'];

                $newResponse=json_encode(['name'=>$name,'profile_picture'=>$profilePic]);

                array_push($newResponse,$finalResponse);
            }

            return response(json_encode($finalResponse),200);
        }

        return response(json_encode($finalResponse),200);
    }
}
