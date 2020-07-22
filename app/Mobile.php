<?php

namespace App;

use App\Exceptions\NoContactFoundedException;
use App\Exceptions\NoValidNumberException;
use App\Interfaces\CarrierInterface;
use App\Services\ContactService;

class Mobile
{

    protected $provider;

    public function __construct(CarrierInterface $provider)
    {
        $this->provider = $provider;
    }

    public function makeCallByName($name = '')
    {
        if (empty($name)) {
            return;
        }

        $contact = ContactService::findByName($name);

        if (empty($contact)) {
            throw new NoContactFoundedException($name, 400);
        }

        $this->provider->dialContact($contact);
        return $this->provider->makeCall();
    }

    public function sendSMSByNumber($number, $body)
    {
        if (empty($number) || empty($body)) {
            return;
        }

        $is_valid = ContactService::validateNumber($number);

        if (!$is_valid) {
            throw new NoValidNumberException($number, 400);
        }

        return $this->provider->sendMessage($number, $body);
    }

}
