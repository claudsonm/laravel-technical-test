<?php

namespace Tests\Unit;

use App\OrderItem;
use PHPUnit\Framework\TestCase;

class OrderItemTest extends TestCase
{
    /** @test */
    public function it_converts_float_values_to_cents()
    {
        $item = new OrderItem(['price' => 15.75]);
        $this->assertIsFloat($item->price);
        $this->assertEquals(1575, $item->price);
    }

    /** @test */
    public function it_assumes_integer_values_are_not_in_cents()
    {
        $item = new OrderItem(['price' => 25]);
        $this->assertIsInt($item->price);
        $this->assertEquals(2500, $item->price);
    }

    /** @test */
    public function it_returns_prices_in_float_format()
    {
        $item = new OrderItem(['price' => 18.84]);
        $this->assertIsFloat($item->float_price);
        $this->assertEquals(18.84, $item->float_price);
    }

    /** @test */
    public function it_can_returns_integer_values_as_float()
    {
        $item = new OrderItem(['price' => 500]);
        $this->assertIsFloat($item->float_price);
        $this->assertEquals(500.0, $item->float_price);
    }
}
