<?php

namespace Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;


use App\Mobile;

class MobileTest extends TestCase
{
	use MockeryPHPUnitIntegration;
	
	// public function tearDown() {
	// 	m::close();
	// }
	
	/** @test */
	public function it_returns_null_when_name_empty()
	{
		$provider = m::mock('App\Interfaces\CarrierInterface');
		$provider->allows()
			->makeCall()
			->andReturn(true);


		$mobile = new Mobile($provider);

		$this->assertNull($mobile->makeCallByName(''));
	}

}
