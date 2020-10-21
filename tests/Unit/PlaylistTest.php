<?php

namespace Tests\Unit;

use App\Models\Playlist;
use App\Models\Movie;
use App\Models\Video;
use Tests\TestCase;

class PlaylistTest extends TestCase
{
    protected $playlist;

    protected function setUp(): void
    {
        parent::setUp();
        $this->playlist = new Playlist();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->playlist);
    }

    public function test_table_name()
    {
        $this->assertEquals('playlists', $this->playlist->getTable());
    }

    public function test_key_name()
    {
        $this->assertEquals('id', $this->playlist->getKeyName());
    }

    public function test_movie_relation()
    {
        $this->belongsTo_relation_test(
            Movie::class,
            'movie_id',
            'id',
            $this->playlist->movie()
        );
    }

    public function test_videos_relation()
    {
        $this->hasMany_relation_test(
            Video::class,
            'playlist_id',
            $this->playlist->videos()
        );
    }
}
