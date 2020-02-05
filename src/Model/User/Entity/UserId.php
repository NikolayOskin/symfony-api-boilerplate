<?php

namespace App\Model\User\Entity;

use Ramsey\Uuid\Uuid;

class UserId
{
    private $uuid;

    public static function create() : UserId
    {
        return new self();
    }

    private function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function asString() : string
    {
        return $this->uuid;
    }
}