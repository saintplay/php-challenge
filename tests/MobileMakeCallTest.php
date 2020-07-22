<?php

namespace Tests;

use App\Call;
use App\Contact;
use App\Exceptions\NoContactFoundedException;
use App\Mobile;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class MobileMakeCallTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    // public function tearDown() {
    //     m::close();
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

    /** @test */
    public function it_returns_call_when_name_is_valid()
    {

        m::mock('alias:App\Services\ContactService')->shouldReceive([
            'findByName' => new Contact(),
            'validateNumber' => true,
        ]);

        $provider = m::mock('App\Interfaces\CarrierInterface');
        $provider->shouldReceive([
            'makeCall' => new Call(),
            'dialContact' => true,
        ]);

        $mobile = new Mobile($provider);

        $this->assertInstanceOf('App\Call', $mobile->makeCallByName('User Test'));
    }

    /** @test */
    public function it_returns_exception_when_no_contact_is_founded()
    {

        m::mock('alias:App\Services\ContactService')->shouldReceive([
            'findByName' => "",
            'validateNumber' => true,
        ]);

        $provider = m::mock('App\Interfaces\CarrierInterface');
        $provider->shouldReceive([
            'makeCall' => new Call(),
            'dialContact' => true,
        ]);

        $mobile = new Mobile($provider);

        $this->expectException(NoContactFoundedException::class);
        $mobile->makeCallByName('User Test');
    }
}
