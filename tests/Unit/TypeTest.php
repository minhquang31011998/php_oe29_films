<?php

namespace Tests\Unit;

use App\Models\Type;
use App\Models\Movie;
use Tests\TestCase;

class TypeTest extends TestCase
{
    protected $type;

    protected function setUp(): void
    {
        parent::setUp();
        $this->type = new Type();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->type);
    }

    public function test_table_name()
    {
        $this->assertEquals('types', $this->type->getTable());
    }

    public function test_key_name()
    {
        $this->assertEquals('id', $this->type->getKeyName());
    }

    public function test_movies_relation()
    {
        $this->belongsToMany_relation_test(
            Movie::class,
            'type_id',
            'movie_id',
            $this->type->movies()
        );
    }
}
