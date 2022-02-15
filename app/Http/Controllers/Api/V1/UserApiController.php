<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Services\UserService;


class UserApiController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create A user
     *
     * @param UserStoreRequest $request
     * @return void
     */
    public function store(UserStoreRequest $request)
    {

        return $this->userService->createUser($request->all());
    }
}
