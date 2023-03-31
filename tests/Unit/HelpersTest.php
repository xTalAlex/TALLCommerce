<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function test_price_label()
    {
        $price = "4.5";

        $this->assertEquals("4.50€", priceLabel($price));
    }
}
