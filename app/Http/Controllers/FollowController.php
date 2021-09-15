<?php

namespace App\Http\Controllers;

use App\Http\Services\FollowService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //フォローし, 前いたページにリダイレクトさせる
    public function add(Request $request)
    {
        $follow = FollowService::follow([
            'following_user_id' => Auth::id(),
            'followed_user_id' => $request['user_id'],
        ]);

        return redirect(url()->previous());
    }

    //アンフォローし, 前いたページにリダイレクトさせる
    public function delete(Request $request)
    {
        $follow = FollowService::unfollow([
            'following_user_id' => Auth::id(),
            'followed_user_id' => $request->user_id,
        ]);

        return redirect(url()->previous());
    }
}
