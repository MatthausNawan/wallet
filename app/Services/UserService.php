<?php

namespace App\Services;

use App\Models\User;
use Exception;

class UserService
{

    private $entity;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }

    /**
     * Create a User
     * exist a observer to auto create your wallet, please check app/Observers/UserObserve.php
     * @param array $data
     * @return void
     */
    public function createUser(array $data)
    {
        try {
            return $this->entity->create($data);
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
