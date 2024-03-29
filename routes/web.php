<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
 use App\Http\Controllers\FollowController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/tweet-form', function () {
        return view('tweet_form', ['path' => 'tweet-form']);
    });

    Route::post('/tweet-form', [TweetController::class, 'add']);

    Route::get('/delete-form', function () {
        return view('delete_form');
    });

    Route::delete('/delete-form', [TweetController::class, 'delete']);

    // ログアウトのトリガーを実装したらこっちを採用
    // Route::get('/dashboard', function () {
    //     return view('dashboard')->name('dashboard');
    //     // return redirect('/home/1');
    // });

    Route::get('/home/{page?}', [TweetController::class, 'index'] );

    Route::get('/mypage', [UserController::class, 'mypage']);

    Route::get('/user-list', [UserController::class, 'show']);

    Route::get('/user/{id?}', [UserController::class, 'userpage']);

    Route::post('/follow', [FollowController::class, 'add']);

    Route::post('/unfollow', [FollowController::class, 'delete']);

    Route::patch('/edit-user-info', [UserController::class, 'edit_user_info']);
});

// ログアウトのトリガーを実装するまで残す
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
