<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ])->with(['posts' => DB::table('posts')->join('users', 'posts.uid', '=', 'users.id')->select('posts.*','users.id as uid','users.name')->inRandomOrder()->limit(20)->get()]);
})->name('home');

Route::get('/posts/{id}', function (Request $request) {
    $post = DB::table('posts')->join('users', 'posts.uid', '=', 'users.id')->where('posts.id',$request->id)->select('posts.*','users.id as uid','users.name')->get();
    return Inertia::render('PostView', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ])->with(['post'=>$post[0]]);
})->name('postview');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function (Request $request) {
    $post_count = DB::table('posts')->select(DB::raw('count(*) as count'))->where('uid',$request->user()->id)->get();
    return Inertia::render('Dashboard')->with(['post_count'=>$post_count[0]->count]);
})->name('dashboard');

require __DIR__.'/post.php';
