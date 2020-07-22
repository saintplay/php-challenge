<?php

namespace App\Interfaces;

use App\Call;
use App\Contact;
use App\Message;

interface CarrierInterface
{

    public function dialContact(Contact $contact);

    public function makeCall(): Call;

    public function sendMessage(string $number, string $body): Message;
}
