<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Services\UserService;
use App\Http\Requests\User\User\UserIndexRequest;
use App\Http\Requests\User\User\UserStoreRequest;
use App\Http\Requests\User\User\UserUpdateRequest;
use App\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    protected UserService $userService;

    /**
     * Constructor
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getSelfInfo()
    {
        try {
            $user = auth()->user();
            return $this->sendResponse(
                'Get self info successfully',
                [$user]
            );
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function updateSelfInfo(UserUpdateRequest $request)
    {
        try {
            $payload = $request->validated();
            $user = $this->userService->updateOne(auth()->user()->id, $payload);

            return $this->sendResponse(
                'Update self info successfully',
                [$user]
            );
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
