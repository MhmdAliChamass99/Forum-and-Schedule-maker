<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\group;
use Auth;
use App\Models\GroupMember;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('Posts.Post');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post=new Post();
        $post->user_id=Auth::id();
        $post->group_id=$request->group_id;
        $post->Status=$request->message;
         
        $image=$request->file('image');
        if($image!=null)
        {
      
        if($image->getClientOriginalExtension()==null)
        {
            return back();
        }
        $input['imagename']=time().'.'.$image->getClientOriginalExtension();
        $destination=public_path('\Images');
        $image->move ($destination,$input['imagename']);
        $post->thumbnail=$input['imagename'];
        $post->save();
        
        $group=group::find($request->group_id);
        $posts=Post::where('group_id',$request->group_id)->orderBy('created_at','desc')->get();

        return \Redirect::route('getposts', $request->group_id);
    }
    else 
    {
        $post->thumbnail=null;
        $post->save();
        $group=group::find($request->group_id);
        $posts=Post::where('group_id',$request->group_id)->orderBy('created_at','desc')->get();

        return \Redirect::route('getposts', $request->group_id);
    }
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
       
        $posts=Post::find($id);

       
        return view('Posts.edit',compact('posts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $post=Post::find($id);
        $post->Status=$request->message;
        $post->update();

        $group=group::find($post->group_id);
        $posts=Post::where('group_id',$post->group_id)->get();

        return view('Groups.GroupDetails',compact('group','posts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        $post=Post::find($id);

    
        Post::where('id',$id)->delete();
        $group=group::find($post->group_id);

        $posts=Post::where('group_id',$post->group_id)->get();
  
        //return view('Groups.GroupDetails',compact('group','posts'));
        return \Redirect::route('getposts', $post->group_id);
    }

    public function createPost($id){

        return view('Posts.Post',compact('id'));
    }
}
