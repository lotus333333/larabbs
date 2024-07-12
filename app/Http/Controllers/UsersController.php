<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
class UsersController extends Controller
{
    //身份验证
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }
    //个人页面的展示
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    //用户编辑资料

    /**
     * @throws AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    //用户编辑资料的提交
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        try {
            $this->authorize('update', $user);
        } catch (AuthorizationException $e) {
        }
        $data = $request->all();

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 416);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}
