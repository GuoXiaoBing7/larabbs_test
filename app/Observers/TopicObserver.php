<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }
    //make_excerpt() 是我们自定义的辅助方法，我们需要在 helpers.php 文件中添加：
    public function saving(Topic $topic){

        $topic->body = clean($topic->body, 'user_topic_body');

        $topic->excerpt = make_excerpt($topic->body);
    }

    public function deleted(Topic $topic){
        \DB::table('replies')->where('topic_id',$topic->id)->delete();
    }
}