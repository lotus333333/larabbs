<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
class UsersController extends Controller
{
    //个人页面的展示
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    //用户编辑资料
    public  function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    //用户编辑资料的提交
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        $data = $request->all();

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}