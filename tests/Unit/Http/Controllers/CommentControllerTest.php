<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\Frontend\CommentController;
use App\Models\Comment;
use App\Models\User;
use App\Models\Movie;
use Tests\TestCase;
use App\Repositories\Comment\CommentRepositoryInterface;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

class CommentControllerTest extends TestCase
{
    use WithoutMiddleware;

    protected $comment;

    public function setUp(): void
    {
        $this->commentMock = Mockery::mock(CommentRepositoryInterface::class);
        $this->commentController = new CommentController(
            $this->commentMock,
        );
        parent::setUp();
    }

    public function tearDown(): void
    {
        unset($this->commentController);
        Mockery::close();
        parent::tearDown();
    }

    public function test_store_comment_with_comment_id()
    {
        $request = [
            'content' => 'test',
            'movie_id' => '1',
            'comment_id' => '1',
        ];

        $requestData = new Request($request);
        $this->commentMock
            ->shouldReceive('storeComment')
            ->once()
            ->with($requestData)
            ->andReturn(true);

        $auth = new User();
        $auth->name = 'auth test';
        $this->be($auth);

        $user = new User();
        $user->id = 1;
        $user->name = 'user test';

        $movie = new Movie();
        $movie->name = 'movie test';

        $this->commentMock
            ->shouldReceive('storeNotification')
            ->once()
            ->andReturn(true);

        $this->commentMock
            ->shouldReceive('getUser')
            ->once()
            ->with($request['comment_id'])
            ->andReturn($user);

        $this->commentMock
            ->shouldReceive('getMovie')
            ->once()
            ->with($request['movie_id'])
            ->andReturn($movie);

        $result = $this->commentController->store($requestData);
        $this->assertTrue($result);
    }

    public function test_store_comment_with_comment_id_null()
    {
        $request = [
            'content' => 'test',
            'movie_id' => '1',
        ];

        $requestData = new Request($request);

        $this->commentMock
            ->shouldReceive('storeComment')
            ->once()
            ->with($requestData)
            ->andReturn(true);

        $result = $this->commentController->store($requestData);
        $this->assertTrue($result);
    }
}
