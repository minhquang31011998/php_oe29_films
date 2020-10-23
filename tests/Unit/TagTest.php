<?php

namespace Tests\Unit;

use App\Models\Tag;
use App\Models\Movie;
use Tests\TestCase;

class TagTest extends TestCase
{
    protected $tag;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tag = new Tag();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->tag);
    }

    public function test_fillable()
    {
        $this->assertEquals([
            'name',
            'slug',
        ], $this->tag->getFillable());
    }

    public function test_table_name()
    {
        $this->assertEquals('tags', $this->tag->getTable());
    }

    public function test_key_name()
    {
        $this->assertEquals('id', $this->tag->getKeyName());
    }

    public function test_movies_relation()
    {
        $this->belongsToMany_relation_test(
            Movie::class,
            'tag_id',
            'movie_id',
            $this->tag->movies()
        );
    }
}
