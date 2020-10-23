<?php

namespace Tests\Unit;

use App\Models\History;
use App\Models\Video;
use Tests\TestCase;

class HistoryTest extends TestCase
{
    protected $history;

    protected function setUp(): void
    {
        parent::setUp();
        $this->history = new History();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->history);
    }

    public function test_table_name()
    {
        $this->assertEquals('histories', $this->history->getTable());
    }

    public function test_key_name()
    {
        $this->assertEquals('id', $this->history->getKeyName());
    }

    public function test_video_relation()
    {
        $this->belongsTo_relation_test(
            Video::class,
            'video_id',
            'id',
            $this->history->video()
        );
    }
}
