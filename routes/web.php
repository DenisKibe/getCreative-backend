<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Articles;
use Illuminate\Http\Request;

//Route::group(['middleware'=>'web'],function(){

	/**
	show Task Dashboard
	*/
//	Route::get('/',function(){
//		$tasks=Task::orderBy('created_at','asc')->get();
//		return view('tasks',[
//			'tasks'=>$tasks
//		]);
//	});
//	/**
//	Add New Task
//	*/
//	Route::post('/task',function(Request $request){
//		$validator=Validator::make($request->all(),[
//			'name'=>'required|max:255',
//		]);
//		if($validator->fails()){
//			return redirect('/')
//			->withInput()
//			->withErrors($validator);
//		}
//		//create The Task
//		$task=new Task;
//		$task->name=$request->name;
//		$task->save();
//
//		return redirect('/');
//	});
	/**
	Delete Task
	*/
//	Route::delete('/task/{task}',function(Task $task){
//		$task->delete();
//		return redirect('/');
//	});
//});

//Authentication Routes
Route::auth();

//Route::get('/tasks','TaskController@index');
//Route::post('/task','TaskController@store');
//Route::delete('/task/{task}','TaskController@destroy');

//Route::resource('photo', 'PhotoController');
//Route::get('user/{user}', function (App\User $user) {
  //  return $user->email;
//});

//Route::get('trying', function(){
	//return response()->json(['name'=>'kamau','state'=>'ca']);
//});
//oute::get('red', function () {
    //return redirect('/trying');
//});

Route::get('/run',function(){
    $status=Artisan::call('migrate',['--path'=>'/database/migrations/adds_userRole_to_users_table.php','--force'=>true,]);
    return '<h1>done</h1>';
});
/**
Route::get('/runInstall',function(){
    $status=Artisan::call('route:list');
    return $status;
});
Route::post('logout', 'Auth\LoginController@logout');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/register', 'Auth\RegisterController@register');
Route::get('/articles', 'ArticlesController@index');
Route::post('/article','ArticlesController@createPost');
Route::put('/edit/{blog_identifier}','ArticlesController@edit');
Route::delete('/deletesoft/{blog_identifier}','ArticlesController@softDelete');
Route::get('/viewdeleted','ArticlesController@getDeleted');
Route::post('/retrivedel','ArticlesController@restoreDeleted');
Route::delete('/permdelete','ArticlesController@permanentDelete');
Route::post('/comment','CommentsController@index');
Route::post('/comments','CommentsController@commentsCreate');
Route::post('/like','LikeController@index');
Route::post('/likes','LikeController@likeIt');
Route::post('/followednumber','FollowersController@followed');
Route::post('/follow','FollowersController@follow');
Route::post('/followedpeople','FollowersController@userFollowed');
Route::post('/followersnumber','FollowersController@followers');
Route::post('/followerspeople','FollowersController@userFollowers');
Route::post('/userInfo','UserController@userInfo');
Route::post('/uploadDp','UserController@uploadPic');
Route::post('/getAvatar','UserController@getDp');

*/
