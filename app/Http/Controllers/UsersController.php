<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHander;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
class UsersController extends Controller
{
    //利用autho中间件进行身份验证，来过滤未登录用户可以修改其他人的信息
    public function __construct()
    {
        $this->middleware('auth',['except'=>['show']]);
    }

    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    public function update(UserRequest $request,ImageUploadHander $uploader,User $user)
    {
        $this->authorize('update',$user);
        $data = $request->all();
        if ($request->avatar){
            $result = $uploader->save($request->avatar,'avatars',$user->id,362);
            //dd($result);
            if ($result){
                $data['avatar'] = $result['path'];
            }

        }

        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功！');

    }
}
