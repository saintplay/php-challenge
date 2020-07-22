<?php

namespace Tests;

use App\Exceptions\NoValidNumberException;
use App\Message;
use App\Mobile;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class MobileSendSMSTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    // public function tearDown() {
    //     m::close();
    // }

    /** @test */
    public function it_returns_null_when_number_empty()
    {
        $provider = m::mock('App\Interfaces\CarrierInterface');
        $provider->allows()
            ->makeCall()
            ->andReturn(true);

        $mobile = new Mobile($provider);

        $this->assertNull($mobile->sendSMSByNumber('', "Hola"));
    }

    /** @test */
    public function it_returns_null_when_body_empty()
    {
        $provider = m::mock('App\Interfaces\CarrierInterface');
        $provider->allows()
            ->makeCall()
            ->andReturn(true);

        $mobile = new Mobile($provider);

        $this->assertNull($mobile->sendSMSByNumber('+51966666666', ""));
    }

    /** @test */
    public function it_returns_message_when_number_and_body_is_valid()
    {

        m::mock('alias:App\Services\ContactService')->shouldReceive([
            'validateNumber' => true,
        ]);

        $provider = m::mock('App\Interfaces\CarrierInterface');
        $provider->shouldReceive([
            'sendMessage' => new Message(),
        ]);

        $mobile = new Mobile($provider);

        $this->assertInstanceOf('App\Message', $mobile->sendSMSByNumber('User Test', 'Hola'));
    }

    /** @test */
    public function it_returns_exception_when_number_is_not_valid()
    {

        m::mock('alias:App\Services\ContactService')->shouldReceive([
            'validateNumber' => false,
        ]);

        $provider = m::mock('App\Interfaces\CarrierInterface');
        $provider->shouldReceive([
            'sendMessage' => new Message(),
        ]);

        $mobile = new Mobile($provider);

        $this->expectException(NoValidNumberException::class);
        $mobile->sendSMSByNumber('User Test', "Hola");
    }
}
