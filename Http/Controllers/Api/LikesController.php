<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Like;
use App\User;
use Illuminate\Support\Facades\Auth;
class LikesController extends Controller
{
    public function like(Request $request)
    {
       $like = Like::where('post_id',$request->id)->where('user_id',Auth::user()->id)->get();
       if(count($like)>0)
       {
           $like[0]->delete();
           return response()->json([
               'sucess'=>true,
               'message'=> 'UNLIKED'
           ]);

       }
       $like = new Like;
       $like->user_id = Auth::user()->id;
       $like->post_id = $request->id;
       $like->save();
       return response()->json([
        'sucess'=>true,
        'message'=> 'liked'
    ]);
    }
}
