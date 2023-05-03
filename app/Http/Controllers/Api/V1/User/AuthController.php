<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\AuthLoginRequest;
use App\Http\Requests\User\Auth\AuthRegRequest;
use App\Http\Requests\User\Auth\AuthStoreRequest;
use App\Resources\AuthResource;
use App\Services\AuthService;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected AuthService $authService;

    /**
     * Constructor
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param AuthLoginRequest $request
     * 
     * @return AuthResource
     */
    public function login(AuthLoginRequest $request)
    {
        try {
            $payload = $request->validated();
            $user = $this->authService->login($payload);
            return $this->sendResponse(
                'Login success',
                $user
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param AuthStoreRequest $request
     * 
     * @return AuthResource
     */
    public function register(AuthRegRequest $request)
    {
        try {
            $payload = $request->validated();
            $user = $this->authService->register($payload);
            return $this->sendResponse(
                'Register success',
                $user
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();
            return $this->sendResponse(
                'Logout success',
                []
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }
}
