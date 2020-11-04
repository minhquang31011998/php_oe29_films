<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\Frontend\RateController;
use App\Models\Rate;
use App\Models\Movie;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Rate\RateRepositoryInterface;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RateControllerTest extends TestCase
{
    use WithoutMiddleware;

    protected $rate;

    public function setUp(): void
    {
        $this->rateMock = Mockery::mock(RateRepositoryInterface::class);
        $this->rateController = new RateController(
            $this->rateMock,
        );
        parent::setUp();
    }

    public function tearDown(): void
    {
        unset($this->rateController);
        Mockery::close();
        parent::tearDown();
    }

    public function test_store_rate_with_oldrate()
    {
        $user = new User();
        $user->id = 2;
        $this->be($user);

        $data = [
            'star' => 2,
            'user_id' => 2,
            'movie_id' => 6,
            'point' => 2,
        ];
        $insertData = new Request($data);

        $oldRate = new Rate();
        $oldRate->id = 2;

        $this->rateMock
            ->shouldReceive('findRate')
            ->with(6, $user->id)
            ->once()
            ->andReturn($oldRate);

        $this->rateMock
            ->shouldReceive('update')
            ->with($oldRate->id, $data)
            ->once()
            ->andReturn(true);

        $movie = new Movie();
        $movie->id = 6;
        $movie->rate = 3;

        $rate1 = new Rate();
        $rate1->point = 1;

        $rate2 = new Rate();
        $rate2->point = 3;

        $averageRate = number_format(($rate1->point + $rate2->point) / 2, config('config.number_after_float'));
        $movieRates = new Collection([
            $rate1,
            $rate2,
        ]);

        $this->rateMock
            ->shouldReceive('findMovie')
            ->with($data['movie_id'])
            ->once()
            ->andReturn($movie);

        $this->rateMock
            ->shouldReceive('findMovieRate')
            ->with($data['movie_id'])
            ->once()
            ->andReturn($movieRates);

        $this->rateMock
            ->shouldReceive('updateMovie')
            ->with($data['movie_id'], $averageRate)
            ->once()
            ->andReturn(true);

        $result = $this->rateController->rate($insertData);

        $this->assertTrue($result);
    }

    public function test_store_rate_with_newrate()
    {
        $user = new User();
        $user->id = 2;
        $this->be($user);

        $data = [
            'star' => 2,
            'user_id' => 2,
            'movie_id' => 6,
            'point' => 2,
        ];
        $insertData = new Request($data);

        $this->rateMock
            ->shouldReceive('findRate')
            ->with(6, $user->id)
            ->once()
            ->andReturn(null);

        $this->rateMock
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn(true);

        $movie = new Movie();
        $movie->id = 6;
        $movie->rate = 3;

        $rate1 = new Rate();
        $rate1->point = 1;

        $rate2 = new Rate();
        $rate2->point = 3;

        $averageRate = number_format(($rate1->point + $rate2->point) / 2, config('config.number_after_float'));
        $movieRates = new Collection([
            $rate1,
            $rate2,
        ]);

        $this->rateMock
            ->shouldReceive('findMovie')
            ->with($data['movie_id'])
            ->once()
            ->andReturn($movie);

        $this->rateMock
            ->shouldReceive('findMovieRate')
            ->with($data['movie_id'])
            ->once()
            ->andReturn($movieRates);

        $this->rateMock
            ->shouldReceive('updateMovie')
            ->with($data['movie_id'], $averageRate)
            ->once()
            ->andReturn(true);

        $result = $this->rateController->rate($insertData);

        $this->assertTrue($result);
    }
}
