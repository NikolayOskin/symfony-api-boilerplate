<?php

namespace App\Model\User\Entity;

interface UserRepositoryInterface
{
    public function hasByEmail(string $email) : bool;

    public function getByEmail(string $email) : User;
}