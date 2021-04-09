<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\RegisteredUserController;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $search_string = urldecode($request->get('search'));
       if($search_string !== null)
       {
            return Inertia::render('Posts/Index', ['filters' => $request->all('search'), 'posts' => DB::table('posts')->where([
                ['uid', $request->user()->id],
                ['title', 'like', '%' . $search_string . '%'],
                ])
                ->orderByDesc('id')->paginate(20)->withQueryString()]);
       }
       else
       {
            return Inertia::render('Posts/Index', ['filters' => $request->all('search'), 'posts' => DB::table('posts')
                ->where('uid', $request->user()->id)->orderByDesc('id')->paginate(20)]);
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'title' => 'required|unique:posts|min:5',
        'comment' => 'required|min:20',
        ]);
        DB::table('posts')->insert(['title' => $request->title, 'body' => $request->comment, 'uid' => $request->user()->id]);

        return Redirect::route('posts.index')->with('success', 'New Post Created');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
        'title' => 'required|min:5',
        'comment' => 'required|min:20',
        ]);
        $updated = DB::table('posts')->where('id', $request->id)->where('uid', $request->user()->id)->update(['title' => $request->title, 'body' => $request->comment]);
        return Redirect::back()->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::table('posts')->where('id', '=', $request->id)->where('uid', $request->user()->id)->delete();
        return Redirect::back()->with('success', 'Post Successfully deleted');
    }

    /**
     * Populate posts table with RSS data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function populate(Request $request) 
    {
        $feed = \Feeds::make('https://news.google.com/news/rss', true); // if RSS Feed has invalid mime types, force to read
        
        foreach($feed->get_items() as $item)
        {
            DB::table('posts')->insert(['title' => $item->get_title(), 'body' => strip_tags($item->get_description()), 'uid' => $request->user()->id]);
        }
        
        return Redirect::back()->with('success', 'Posts Successfully Pulled From Google News RSS');
    }
}
