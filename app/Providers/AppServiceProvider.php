<?php

namespace App\Providers;
use App\Models\Reply;
use App\Models\Topic;
use App\Observers\ReplyObserver;
use App\Observers\TopicObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //boot方法中设置了写框架初始化进行的操作
        //设置语言
        \Carbon\Carbon::setLocale('zh');
        //扩大数据库字符串长度
        Schema::defaultStringLength(191);
        //注册观察器，得写在boot里，不然TopicObserver不执行，也就是不会执行创建文章之前进行的操作（包括 生成make_excerpt截词 和 防止XSS攻击的HtmlPurifier插件的使用 ）
        Topic::observe(TopicObserver::class);
        Reply::observe(ReplyObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
