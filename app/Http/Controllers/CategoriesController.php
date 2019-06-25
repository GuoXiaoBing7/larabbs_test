<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //

    public function show(Category $category,Request $request,Topic $topic)
    {
        //with() 预加载 避免N+1的问题
        //withorder()调用Topic.php 模型中的scopeWithOrder来匹配是最新回复的还是最新发表的
       $topics = $topic->where('category_id',$category->id)
                    ->withOrder($request->order)
                    ->paginate(20);
       return view('topics.index',compact('topics','category'));
    }
}
