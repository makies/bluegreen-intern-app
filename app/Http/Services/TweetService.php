<?php

namespace App\Http\Services;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TweetService
{

    // 引数
    //      tweet_textとuser_idをもつ連想配列
    // 動作
    //      user_idのツイートとしてtweet_textをDBに保存する
    public static function create_tweet($request)
    {
        $tweet = Tweet::create([
            'tweet_text' => $request['tweet_text'],
            'user_id' => $request['user_id'],
        ]);

        return $tweet;
    }

    // 引数
    //      id(tweet_id)とuser_idをもつ連想配列
    // 動作
    //      ツイートがuser_idのものなら, DBから削除する
    public static function delete_tweet($request)
    {
        $tweet = Tweet::find($request['id']);
        if ($tweet && $tweet->user_id == $request['user_id']) {
            $tweet->delete();
        }

        return $tweet;
    }

    // 引数
    //      user_idとpageをもつ連想配列
    // 動作
    //      このpageを表示するツイートを新しい順の配列で返す.
    //      表示するツイート
    //          - user_idがフォローしているユーザのツイート
    public const GET_MAX_TWEET_NUM = 10; //1ページの表示ツイート数上限

    public static function get_tweets_at_page($request)
    {
        $skip_tweet_cnt = self::GET_MAX_TWEET_NUM * ($request['page'] - 1); //offsetする数

        return $tweets = DB::table('tweets')
            ->join('follows', function ($join) use ($request) {
                $join->on('tweets.user_id', '=', 'follows.followed_user_id')
                    ->where('follows.following_user_id', '=', $request['user_id']);
            })
            ->join('users', 'tweets.user_id', '=', 'users.id')
            ->orderBy('tweets.id')
            ->skip($skip_tweet_cnt)
            ->take(self::GET_MAX_TWEET_NUM)
            ->select('tweets.id', 'tweets.tweet_text', 'tweets.user_id', 'users.name', 'tweets.created_at')
            ->get();
    }

    // 引数
    //      user_idとpageをもつ連想配列
    // 動作
    //      ホームに表示されるツイートが何ページ数に分かれるか
    public static function get_page_num($request)
    {
        $tweet_count = DB::table('tweets')
            ->join('follows', function ($join) use ($request) {
                $join->on('tweets.user_id', '=', 'follows.followed_user_id')
                    ->where('follows.following_user_id', '=', $request['user_id']);
            })
            ->count();

        return ($tweet_count + self::GET_MAX_TWEET_NUM - 1) / self::GET_MAX_TWEET_NUM;
    }
}
