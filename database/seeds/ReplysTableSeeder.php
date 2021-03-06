<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;
class ReplysTableSeeder extends Seeder
{
    public function run()
    {

        // 所有用户 ID 数组，如：[1,2,3,4]
        $user_ids = User::all()->pluck('id')->toArray();
        //所有话题topicID的数据
        $topic_ids = Topic::all()->pluck('id')->toArray();
        //获取faker实例
        $faker = app(Faker\Generator::class);
        $replys = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function ($reply, $index) use ($user_ids,$topic_ids,$faker)
            {
                //从用户组id任意取一个放入到reply数据中
                $reply->user_id = $faker->randomElement($user_ids);
                //从话题组组id任意取一个放入到reply数据中
                $reply->topic_id = $faker->randomElement($topic_ids);
        });

        Reply::insert($replys->toArray());
    }

}

