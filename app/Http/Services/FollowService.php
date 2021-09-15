<?php

namespace App\Http\Services;

use App\Models\Tweet;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\DB;

class FollowService
{

    // 引数
    //      following_user_idとfollowed_user_idを持つ連想配列
    // 動作
    //      following_user_id が followed_user_id をフォローしているかを返す(boolean)
    public static function is_following($request)
    {
        $record_cnt = DB::table('follows')
        ->where([
            ['following_user_id', '=', $request['following_user_id']],
            ['followed_user_id', '=', $request['followed_user_id']]
        ])
        ->count();

        return $record_cnt >= 1;
    }

    /**
     *  following_user_id が followed_user_id をフォローするレコードを追加
     *
     *  @param array $request following_user_idとfollowed_user_idを持つ連想配列
     *  @return object 作成したレコード
     */
    public static function follow(array $request): object
    {
        return Follow::firstOrCreate([
            'following_user_id' => $request['following_user_id'],
            'followed_user_id' => $request['followed_user_id'],
        ]);
    }

    /**
     *  following_user_id が followed_user_id をフォローしているレコードを削除
     *
     *  @param array $request following_user_idとfollowed_user_idを持つ連想配列
     *  @return object 削除したレコード
     */
    public static function unfollow(array $request): object
    {
        return DB::table('follows')
            ->where([
                ['following_user_id', '=', $request['following_user_id']],
                ['followed_user_id', '=', $request['followed_user_id']]
            ])
            ->delete();
    }
}
