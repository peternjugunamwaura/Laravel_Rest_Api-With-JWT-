<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use App\User;

class CommentsController extends Controller
{
    public function create(Request $request)
    {
          $comment = new  Comment;
          $comment->user_id = Auth::user()->id;
          $comment->post_id = $request->id;
          $comment->comment= $request->comment;
          $comment->save();

          return response()->json([
              'success'=>true,
              'messages'=>'comment added'
          ]);

    }
    public function update(Request $request)
    {
        $comment = Comment::find($request->id);
        //check if the user is editing there own comments
        if($comment->id != Auth::user()->id)
        {

            return response()->json([
                'success'=>false,
                'messages'=>'unauthorised access'
            ]);

        }
        $comment->comment = $request->comment;
        $comment->update();
        return response()->json([
            'success'=>true,
            'messages'=>'Comment edited successfully'
        ]);

    }
    public function delete(Request $request)
    {
        $comment = Comment::find($request->id);
        //check if the user is editing there own comments
        if($comment->id != Auth::user()->id)
        {

            return response()->json([
                'success'=>false,
                'messages'=>'unauthorised access'
            ]);

        }
      
        $comment->delete();
        return response()->json([
            'success'=>true,
            'messages'=>'Comment deleted successfully'
        ]);

    }
    public function comments(Request $request)
    {
        $comments = Comment::where('post_id',$request->id)->get();
        //show user of each comment
        foreach($comments as $comment)
        {
            $comment->user;

        }
        return response()->json([
            'success'=>true,
            'comments'=>$comments
        ]);
    }
}
