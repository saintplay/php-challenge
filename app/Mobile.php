<?php

namespace App;

use App\Exceptions\NoContactFoundedException;
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

}
