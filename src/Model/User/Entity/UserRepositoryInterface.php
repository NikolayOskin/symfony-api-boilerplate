<?php

namespace App\Model\User\Entity;

interface UserRepositoryInterface
{
    public function hasByEmail(Email $email) : bool;

    public function getByEmail(Email $email) : User;
}