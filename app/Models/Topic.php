<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    //这里我们使用了 Laravel 本地作用域 。
    //本地作用域允许我们定义通用的约束集合以便在应用中复用。
    //要定义这样的一个作用域，只需简单在对应 Eloquent 模型方法前加上一个 scope 前缀，作用域总是返回 查询构建器。
    //一旦定义了作用域，则可以在查询模型时调用作用域方法。在进行方法调用时不需要加上 scope 前缀。
    //如以上代码中的 recent() 和 recentReplied()。
    public function scopeWithOrder($query,$order){
        switch ($order){
            case 'recent':
                $query->recent();
                break;
            default :
                $query->recentReplied();
        }
        return $query->with('category','user');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at','desc');
    }

    // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
    // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
    public function scopeRecentReplied($query){
        return $query->orderBy('updated_at','desc');
    }
}
