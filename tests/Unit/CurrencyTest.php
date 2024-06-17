<?php

namespace Tests\Unit;

use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function test_convert_usd_to_euro_successful()
    {
        $amount = (new CurrencyService())->convert(100, 'usd', 'eur');
        $this->assertEquals(98, $amount);
    }
    public function test_convert_usd_to_npr_successful()
    {
        $amount = (new CurrencyService())->convert(1, 'usd', 'npr');
        $this->assertEquals(133.60, $amount);
    }
    public function test_convert_usd_to_inr_not_successful()
    {
        $amount = (new CurrencyService())->convert(1, 'usd', 'inr');
        $this->assertNotEquals(100, $amount);
    }
    public function test_convert_usd_to_jpn_returns_zero()
    {
        $amount = (new CurrencyService())->convert(1, 'usd', 'jpn');
        $this->assertEquals(0, $amount);
    }
}
