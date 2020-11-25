<?php

namespace Tests\Unit;

use App\Models\Option;
use App\Models\OptionValue;
use Tests\TestCase;

class OptionValueTest extends TestCase
{
    protected $optionValue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->optionValue = new optionValue();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->optionValue);
    }

    public function test_table_name()
    {
        $this->assertEquals('option_values', $this->optionValue->getTable());
    }

    public function test_key_name()
    {
        $this->assertEquals('id', $this->optionValue->getKeyName());
    }

    public function test_option_relation()
    {
        $this->belongsTo_relation_test(
            Option::class,
            'option_id',
            'id',
            $this->optionValue->Option()
        );
    }
}
