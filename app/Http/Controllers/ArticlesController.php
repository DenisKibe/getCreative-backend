<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articles;
use App\Categorys;
use App\Http\Requests;
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


class ArticlesController extends Controller
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
        if(!$request->isMethod('get'))
        {
            return response()->json(['message'=>'Request Method not allowed'], 405);
        }
        //function to convert stdclass object to array.
        function objectToArray($d){
            if(is_object($d)){
                //get the properties of the given object
                //with get object vars function
                $d=get_object_vars($d);
            }
            if(is_array($d)){
                /**
                 * return object converted to array
                 * using __function__(magic constant)
                 * for recursive call
                 */
                return array_map(__FUNCTION__, $d);
            }
            else{
                //return array
                return $d;
            }
        }

        $articles=Articles::select('id','blog_identifier','title')->get();
        $textPath=Articles::pluck('text_path');
        $categoryid=Articles::pluck('category_id');
        $finalResponse=array();
        //loop through the returned array
        for($i=0;$i<count($articles);$i++)
        {

            //for the textPath
            $contents=Storage::get($textPath[$i]);

            //get category name and category id from category table.
            $category=Categorys::select('category_id','category')->where('category_id',$categoryid[$i])->first();
            //convert to array
            $category=$category->toArray();


            //convert from json object returned to array
            $newResponse=json_decode($articles[$i]);

            //call function to convert from stdclass object to array.
            $newResponse=objectToArray($newResponse);

            //append the text key and value to the new response
            $newResponse=array_add($newResponse,'text',$contents);
            $newResponse=array_add($newResponse,'category_id',$category['category_id']);
            $newResponse=array_add($newResponse,'category_name',$category['category']);

            //convert the array into json
            $newResponse=json_encode($newResponse);


            //push the json objects to an array
            array_push($finalResponse,$newResponse);
        }

        //return the response in json
        return response(json_encode($finalResponse), 200);

    }

    /**
     * to create a blog post.
     * needed
     *      blog_identifier,user_id,title,text_path,category_id
     */
    public function createPost(Request $request)
    {
        if(!$request->isMethod('post'))
        {
            return response()->json(['message'=>'Request Method not allowed'], 405);
        }

        //function to return the textpath of an article
        function textPath($textt){
            $textContent=$textt;
            $text_path=genIdentifier();
            Storage::put($text_path.'.txt',$textContent);
            return $text_path.'.txt';
        }

        //obtain the category id from the category table..
        function category_id($categoryName){
            $categoryId=Categorys::select('category_id')->where('category',$categoryName)->first();

            $categoryId=$categoryId->toArray();

            return $categoryId['category_id'];
        }

        $this->validate($request,[
            'title'=>'required|max:100',
            'text'=>'required|max:500',
            'categoryId'=>'required',
        ]);
        //create the blog
        $request->user()->articles()->create([
            'blog_identifier'=>genIdentifier(),
            'title'=>$request->title,
            'text_path'=>textPath($request->text),
            'category_id'=>category_id($request->categoryId),
        ]);
        return response()->json(['Message'=>'Article created successifully.'], 201);
    }

    /**
     * for edit articles
     */
    public function  edit(Request $request, $blog_identifier)
    {

        $this->validate($request,[
            //'article_identifier'=>'required',
            'newText'=>'required|min:100'|'string',
            'newTitle'=>'max:250'|'string',
        ]);

        //update
        if(!($request->newTitle)==""){
            $request->user()->articles()->where('blog_identifier',$blog_identifier)->update([
                'title'=>$request->newTitle,
            ]);
        }

        $textPath=Articles::select('text_path')->where([
            ['blog_identifier',$blog_identifier],
            ['user_id',$request->user()->userId],
            ])->first();
        $textPath=$textPath->toArray();
        $textPath=$textPath['text_path'];

        Storage::put($textPath,$request->text);

        return response()->json(['Message'=>'Article edited successifully.'], 200);

    }

    /**
     * for the soft delete articles
     */
    public function softDelete(Request $request, $blog_identifier)
    {
        //$this->validate($request,[
        //    'blog_identifier'=>'required',
       // ]);

        //check if the user owns the ariticle
        $check=Articles::select('text_path')->where([['user_id',$request->user()->userId],
                    ['blog_identifier',$blog_identifier],
        ])->get();

        if(count($check)>0)
        {
            Articles::where([['user_id',$request->user()->userId],
                    ['blog_identifier',$blog_identifier],
        ])->delete();

        return response()->json(['Message'=>'Article deleted successfully'], 200);
        }else{
            return response()->json(['Message'=>'Failed.'], 400);
        }

    }

    /**
    * delete the articles permanently
    */
    public function permanentDelete(Request $request, $blog_identifier)
    {
        //$this->validate($request,[
         //   'blog_identifier'=>'required',
        //]);

        //check if the user owns the ariticle
        $check=Articles::onlyTrashed()->select('text_path')->where([['user_id',$request->user()->userId],
                    ['blog_identifier',$blog_identifier],
        ])->get();

        if(count($check)>0)
        {
            $text=Articles::onlyTrashed()->where([['user_id',$request->user()->userId],
                        ['blog_identifier',$blog_identifier],
            ])->pluck('text_path');
            Articles::where([['user_id',$request->user()->userId],
                    ['blog_identifier',$blog_identifier],
        ])->forceDelete();

        $deletedPath='Deleted/'.$text[0];
        Storage::move($text[0],$deletedPath);

        return response()->json(['Message'=>'Article has been permanently Deleted. This process cannot be reversed'], 200);
        }else{
            return response()->json(['Message'=>'failed'], 400);
        }
    }

    /**
     * return only the soft deleted models for a user
     */
    public function getDeleted(Request $request)
    {
        $DeletedArticles=Articles::onlyTrashed()->select('id','blog_identifier','title')->where('user_id',$request->user()->userId)->get();
        $textPathDel=Articles::onlyTrashed()->where('user_id',$request->user()->userId)->pluck('text_path');
        $categoryDel=Articles::onlyTrashed()->where('user_id',$request->user()->userId)->pluck('category_id');

        $finalResponseDel=array();

        for($i=0;$i<count($DeletedArticles);$i++)
        {
            //get contents from the text path
            $content=Storage::get($textPathDel[$i]);

            //get the category name
            $CategoryDel=Categorys::select('category_id','category')->where('category_id',$categoryDel[$i])->first();

            //convert the object to array
            $CategoryDel=$CategoryDel->toArray();

            //convert the return article object to array
            $NewDeletedArticles=$DeletedArticles[$i]->toArray();

            //append the contents, category_id and category_name to the final array
            $NewDeletedArticles=array_add($NewDeletedArticles,'article',$content);
            $NewDeletedArticles=array_add($NewDeletedArticles,'category_id',$CategoryDel['category_id']);
            $NewDeletedArticles=array_add($NewDeletedArticles,'category_name',$CategoryDel['category']);

            //convert to an object
            $NewDeletedArticles=json_encode($NewDeletedArticles);

            //push the object to the final response array
            array_push($finalResponseDel,$NewDeletedArticles);
        }

        return response(json_encode($finalResponseDel), 200);
    }

    /**
     * restore a sof deleted article
     */
    public function restoreDeleted(Request $request)
    {
        $this->validate($request,[
            'article_identifier'=>'required',
        ]);

        Articles::where([['user_id',$request->user()->userId],
                          ['blog_identifier',$request->article_identifier],
        ])->restore();

        return response()->json(['Message'=>'Article has been Successfully restored.'], 200);
    }
}
