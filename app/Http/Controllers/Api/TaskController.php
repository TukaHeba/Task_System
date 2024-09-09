<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\ApiResponseService;

class TaskController extends Controller
{
    /**
     * The task service instance.
     * @var TaskService
     */
    protected $taskService;

    /**
     * TaskController constructor.
     * 
     * @param TaskService $taskService The task service instance.
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the tasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $tasks = $this->taskService->listAllTasks();
            return ApiResponseService::paginated($tasks, TaskResource::class, 'Tasks retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \App\Http\Requests\StoreTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        try {
            $task = $this->taskService->createTask($validated);
            return ApiResponseService::success(new TaskResource($task), 'Task created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $task = $this->taskService->showTask($id);
            return ApiResponseService::success(new TaskResource($task), 'Task retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateTaskRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        $validated = $request->validated();
        try {
            $task = $this->taskService->updateTask($id, $validated);
            return ApiResponseService::success(new TaskResource($task), 'Task updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $this->taskService->deleteTask($id);
            return ApiResponseService::success(null, 'Task deleted successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error('An error occurred on the server.', 500);
        }
    }
}
