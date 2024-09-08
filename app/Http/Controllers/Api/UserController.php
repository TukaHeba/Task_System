<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\ApiResponseService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * The user service instance.
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     * 
     * @param UserService $userService The user service instance.
     * Admins can access all methods
     * Clients can access all methods except 'index'
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();
            return ApiResponseService::success(UserResource::collection($users), 'Users retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = $this->userService->createUser($validated);

            return ApiResponseService::success(new UserResource($user), 'User created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Display the specified user.
     *
     * @param User $user The user object to show.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        try {
            $user = $this->userService->showUser($user);
            return ApiResponseService::success(new UserResource($user), 'User retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user The user to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        try {
            $updatedUser = $this->userService->updateUser($user, $validated);

            return ApiResponseService::success(new UserResource($updatedUser), 'User updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user The user to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        try {
            $deleted = $this->userService->deleteUser($user);

            if ($deleted) {
                return ApiResponseService::success(null, 'User deleted successfully', 200);
            } else {
                return ApiResponseService::error('Failed to delete the user.', 400);
            }
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }
}
