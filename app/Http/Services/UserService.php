<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{

    /**
     *  ユーザ名, メールアドレス, 自己紹介文を変更する
     *
     *  @param array user_idとnameを持つ連想配列
     *  @return void
     */
    public static function edit_user_info($request)
    {
        $user = User::find($request['user_id']);
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->introduction = $request['introduction'];
        $user->save();
    }
}
