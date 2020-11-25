<?php
namespace App\Repositories\Comment;

interface CommentRepositoryInterface
{
    public function storeComment($request);

    public function getUser($id);

    public function getMovie($id);

    public function getComments($request);

    public function getReplyComments($commentId);

    public function storeNotification($user, $data);
}
