<?php

namespace Tests\Unit;

use App\Models\Source;
use App\Models\Channel;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    protected $channel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->channel = new Channel();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->channel);
    }

    public function test_fillable()
    {
        $this->assertEquals([
            'title',
            'link',
            'description',
            'channel_type',
            'status',
            'user_id',
        ], $this->channel->getFillable());
    }

    public function test_table_name()
    {
        $this->assertEquals('channels', $this->channel->getTable());
    }

    public function test_key_name()
    {
        $this->assertEquals('id', $this->channel->getKeyName());
    }

    public function test_sources_relation()
    {
        $this->hasMany_relation_test(
            Source::class,
            'channel_id',
            $this->channel->sources()
        );
    }
}
