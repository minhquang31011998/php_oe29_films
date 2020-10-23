<?php

namespace Tests\Unit;

use App\Models\Option;
use App\Models\OptionValue;
use Tests\TestCase;

class OptionTest extends TestCase
{
    protected $option;

    protected function setUp(): void
    {
        parent::setUp();
        $this->option = new Option();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->option);
    }

    public function test_table_name()
    {
        $this->assertEquals('options', $this->option->getTable());
    }

    public function test_key_name()
    {
        $this->assertEquals('id', $this->option->getKeyName());
    }

    public function test_optionValues_relation()
    {
        $this->hasMany_relation_test(
            OptionValue::class,
            'option_id',
            $this->option->optionValues()
        );
    }
}
