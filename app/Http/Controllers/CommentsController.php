<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comments;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    //assign the auth middleware
    public function __construct()
    {
       $this->middleware('auth');
    }
    /**
     * return all the articles.
     */

    public function index(Request $request)
    {
        $this->validate($request,[
            'article_id'=>'required',
        ]);

            //return an object of the comment
        $comments=Comments::select('comment','comment_identifier','user_id')->where('article_id',$request->article_id)->get();
        $finalResponse=array();
        //loop through the returned array and access each object
        for($i=0;$i<count($comments);$i++)
        {
            $obj=$comments[$i]->toArray();
            $userid=array_pull($obj,'user_id');
            $userName=User::select('name')->where('userId',$userid)->first();
            $userName=$userName->toArray();
            $userName=$userName['name'];
            $newComment=array_add($obj,'Name',$userName);
            $newComment=json_encode($newComment);

            array_push($finalResponse,$newComment);

        }


           return response(json_encode($finalResponse),200);

    }

    /**
     * for the comment
     * needed
     * comment identifier, comment,article_id
     */
    public function commentsCreate(Request $request)
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
            'commentText'=>'reiquired|max:50',
        ]);
        //save the comment
        $request->user()->comments()->create([
            'comment_identifier'=>genIdentifier(),
            'article_id'=>$request->article_id,
            'comment'=>$request->comment,
        ]);

        return response()->json(['Message'=>'Comment created successiful.'],201);
    }
}
