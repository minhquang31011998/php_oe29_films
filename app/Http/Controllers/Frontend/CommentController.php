<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentNotification;
use App\Models\Movie;
use Pusher\Pusher;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentController extends Controller
{
    protected $comment;

    public function __construct(CommentRepositoryInterface $comment)
    {
        $this->comment = $comment;
    }

    public function store(Request $request)
    {

        $result = $this->comment->storeComment($request);
        if ($request->get('comment_id') != null) {
            $user = $this->comment->getUser($request->get('comment_id'));
            $movie = $this->comment->getMovie($request->get('movie_id'));
            $data = [
                'nameUser' => Auth::user()->name,
                'nameMovie' => $movie->name,
                'idUser' => $user->id,
            ];
            $this->comment->storeNotification($user, $data);
            $options = array(
                'cluster' => 'ap1',
                'encrypted' => true
            );

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            return $pusher->trigger('Notification', 'send-message', $data);
        }

        return $result;
    }

    public function getData(Request $request)
    {
        $comments = $this->comment->getComments($request);
        $data = null;
        foreach ($comments as $comment) {
            $data .=
                '<li class="comments__item">
                    <div class="comments__autor">
                        <span class="comments__name">' . $comment['user']['name'] . '</span>
                        <span class="comments__time">' . $comment['created_at'] . '</span>
                    </div>
                    <p class="comments__text">' . $comment['content'] . '</p>
                    <div class="comments__actions">
                        <button type="button" class="reply" id="' . $comment['id'] . '"><i class="icon ion-ios-share-alt"></i>' . trans('reply') . '
                        </button>
                        <button type="button" class="delete" id="' . $comment['id'] . '"><i class="fa fa-times"></i>' . trans('delete') . '
                        </button>
                    </div>
                </li>
                ';
            $data .= $this->getReplyData($comment['id']);
        }

        return $data;
    }

    public function getReplyData($commentId)
    {
        $comments = $this->comment->getReplyComments($commentId);
        $data = null;
        foreach ($comments as $comment) {
            $data .=
                '<li class="comments__item comments__item--answer">
                    <div class="comments__autor">
                        <span class="comments__name">' . $comment['user']['name'] . '</span>
                        <span class="comments__time">' . $comment['created_at'] . '</span>
                    </div>
                    <p class="comments__text">' . $comment['content'] . '</p>
                    <div class="comments__actions">
                        <button type="button" class="delete" id="' . $comment['id'] . '"><i class="fa fa-times"></i>' . trans('delete') . '
                        </button>
                    </div>
                </li>
                ';
        }

        return $data;
    }

    public function destroy($commentId)
    {
        $user = $this->comment->getUser($commentId);
        if (Auth::user()->role != config('config.role_admin') && Auth::user()->id != $user->id) {
            return false;
        }
        $msg = trans('success');

        return $this->comment->delete($commentId);
    }

    public function markReadNoti()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }
}
