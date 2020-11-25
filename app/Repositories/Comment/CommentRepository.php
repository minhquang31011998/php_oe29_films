<?php
namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\Movie;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentNotification;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function getModel()
    {
        return Comment::class;
    }

    public function storeComment($request)
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $comment->movie_id = $request->get('movie_id');
        $comment->parent_id = $request->get('comment_id');
        $comment->status = config('config.status_active');
        return $comment->save();
    }

    public function getUser($id)
    {
        return Comment::find($id)->user()->first();
    }

    public function getMovie($id)
    {
        return Movie::find($id);
    }

    public function getComments($request)
    {
        $comments = Comment::where([
            ['parent_id', null],
            ['movie_id', $request->get('movie_id')],
        ])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        return $comments;
    }

    public function getReplyComments($commentId)
    {
        $comments = Comment::where('parent_id', $commentId)
            ->with('user')
            ->get()
            ->toArray();

        return $comments;
    }

    public function storeNotification($user, $data)
    {
        return $user->notify(new CommentNotification($data));
    }
}
