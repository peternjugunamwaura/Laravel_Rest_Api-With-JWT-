<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\User;

class PostsController extends Controller
{
    public function create(Request $request)
    {
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post -> desc = $request->desc;
        if($request->photo !='')
        {
            $photo = time().'jpg';
            file_put_contents('storage/posts/'.$photo,base64_decode($request->photo));
            $post->photo=$photo;
        }
        $post->save();
        $post->user;
        return response()->json([
            'success'=>true,
            'message' => 'posted',
            'post'=>$post
        ]);
    }
    public function update(Request $request)
    {
        $post = Post::find($request->id);
        if(Auth::user()->id !=$request->id)
        {
            return response()->json([
                'success'=> false,
                'message' => 'unauthorized access'
            ]);
        }
            $post ->desc = $request->desc;
            $post->update();
            return response()->json([
                'success'=> true,
                'message'=>"post edited"
            ]);
        
    }
    public function delete(Request $request)
    {
        $post = Post::find($request->id);
        if(Auth::user()->id !=$request->id)
        {
            return response()->json([
                'success'=> false,
                'message' => 'unauthorized access'
            ]);
        }

            // check if the post has a photo to delete
            if($post->photo != '')
            {
                storage::delete('public/posts/'.$post->photo);
            }
            $post -> delete();
            return response()->json([
                'success'=> true,
                'message'=>"post deleted"
            ]);
        
    }
    public function posts()
    {
        $posts = Post::orderBy('id','desc')->get();
        foreach($posts as $post)
        {
                $post->user;
                //comments count
                $post['commentsCount'] = count($post->comments);
                $post['likesCount'] = count($post->likes);
                //check if user liked their own posts
                foreach($post->likes as $like)
                {
                    if($like->user_id == Auth::user()->id)
                    {
                        $post['selfLike']=true;
                    }
                }
        }
        return response()->json([
            'success'=>true,
            'posts' => $posts
        ]);
    }
}
