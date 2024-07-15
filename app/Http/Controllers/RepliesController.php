<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(ReplyRequest $request)
    {
        $content = $request->input('content');
        $topicId = $request->input('topic_id');
        $userId = Auth::id();

        // 创建一个新的Reply实例
        $reply = new Reply();
        $reply->content = $content;
        $reply->user_id = $userId;
        $reply->topic_id = $topicId;

        // 尝试保存回复
        if ($reply->save()) {
            // 保存成功
            return redirect()->to($reply->topic->link())->with('success', '评论创建成功！');
        } else {
            // 保存失败
            return back()->withErrors(['error' => '评论保存失败，请重试。'])->withInput();
        }
    }
    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();

        return redirect()->route('replies.index')->with('success', '评论删除成功！');
    }
}
