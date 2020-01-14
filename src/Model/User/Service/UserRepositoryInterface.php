<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\User;

interface UserRepositoryInterface
{
    public function hasByEmail(string $email) : bool;

    public function getByEmail(string $email) : User;
}