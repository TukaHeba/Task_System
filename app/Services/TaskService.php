<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AssignTaskRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskService
{
    /**
     * List all tasks with pagination based on user role.
     * Eager loads the user relationship for each task.
     * 
     * Admin can list all tasks.
     * Manager can list all tasks created by him.
     * User can list all tasks that assigend to him.

     * @return \Illuminate\Pagination\LengthAwarePaginator 
     * @throws \Exception If the user role is invalid or an error occurs.
     */
    public function listAllTasks($priority = null, $status = null)
    {
        try {
            $user = Auth::user();
            $query = Task::with('user');

            switch ($user->role) {
                case 'admin':
                    break;

                case 'manager':
                    $query->where('created_by', $user->id);
                    break;

                case 'user':
                    $query->where('assigned_to', $user->id);
                    break;

                default:
                    throw new \Exception('Invalid user role');
            }

            // Apply filters if provided
            if ($priority) {
                $query->priority($priority);
            }

            if ($status) {
                $query->status($status);
            }

            return $query->paginate();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tasks: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Create a new task with the given data.
     *
     * Only admins and managers can create new tasks
     * (I handle that in authorize method in StoreTaskRequest)
     * 
     * @param array $data An array of task attributes to be created.
     * @return \App\Models\Task The created task instance.
     * @throws \Exception If task creation fails.
     */
    public function createTask(array $data): Task
    {
        try {
            return Task::create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create task: ' . $e->getMessage());
            throw new \Exception('An error occurred while creating the task.');
        }
    }

    /**
     * Retrieve and return a task based on the user’s role and authorization.
     *
     * Admin can show any task.
     * Manager can show the task only if he created it.
     * User can show the task only if it assigend to him.
     *
     * @param int $taskId The ID of the task to retrieve.
     * @throws \Exception If the task not exisit, or if the user is not authorized to view it.
     * @return \App\Models\Task The task object that matches the provided ID.
     */
    public function showTask(int $taskId)
    {
        try {
            $user = Auth::user();
            $task = Task::with('user')->findOrFail($taskId);

            switch ($user->role) {
                case 'admin':
                    return $task;

                case 'manager':
                    if ($task->created_by !== $user->id) {
                        throw new \Exception('You are not authorized to view this task.');
                    }
                    return $task;

                case 'user':
                    if ($task->assigned_to !== $user->id) {
                        throw new \Exception('You are not authorized to view this task.');
                    }
                    return $task;

                default:
                    throw new \Exception('An error occurred on the server.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to retrieve task: ' . $e->getMessage());
            throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Update a task based on its ID.
     * 
     * Admin can update any task
     * manager can update only tasks created by him
     * user can update only the status of tasks that assigned to him
     * 
     * @param int $taskId
     * @param array $data
     * @throws \Exception
     * @return Task|Task[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function updateTask(int $taskId, array $data)
    {
        try {
            $task = Task::findOrFail($taskId);
            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    $task->update($data);
                    break;

                case 'manager':
                    if ($task->created_by === $user->id) {
                        $task->update($data);
                    } else {
                        throw new \Exception('Managers can only update tasks they created.');
                    }
                    break;

                case 'user':
                    if ($task->assigned_to === $user->id) {
                        if (isset($data['status'])) {
                            $task->update([
                                'status' => $data['status'],
                                'updated_on' => now(),
                            ]);
                        } else {
                            throw new \Exception('Users can only update the status.');
                        }
                    } else {
                        throw new \Exception('Unauthorized: Users can only update tasks assigned to them.');
                    }
                    break;

                default:
                    throw new \Exception('Unauthorized action.');
            }

            return $task;
        } catch (\Exception $e) {
            throw new \Exception('An unexpected error occurred.');
        }
    }

    /**
     * Delete a task based on its ID.
     * 
     * This method handles task deletion by checking the user's role:
     * Admincan delete any task
     * Manager can delete only tasks he created it
     * User can not delete any task
     * 
     * @param int $taskId The ID of the task to be deleted.
     * @throws \Exception If the user is not authorized or has user role
     * @return bool `true` if the task is deleted successfully 
     */

    public function deleteTask(int $taskId)
    {
        $user = Auth::user();
        $task = Task::findOrFail($taskId);

        switch ($user->role) {
            case 'admin':
                return $task->delete();

            case 'manager':
                if ($task->created_by !== $user->id) {
                    throw new \Exception('You are not authorized to delete this task.');
                }
                return $task->delete();

            default:
                throw new \Exception('An error occurred on the server.');
        }
    }

    /**
     * Assign a task to a user.
     *
     * Ensure the user ID is valid
     * Update the assigned_to field
     * Handle cases where the task or user is not found
     * Handle invalid user id
     * 
     * @param int $taskId
     * @param int $userId
     * @return Task
     * @throws ModelNotFoundException
     * @throws InvalidArgumentException
     */
    public function assignTask(int $taskId, int $userId)
    {
        try {
            $task = Task::findOrFail($taskId);

            if (!User::find($userId)) {
                throw new InvalidArgumentException('User not found.');
            }

            $task->update(['assigned_to' => $userId]);

            return $task;
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Task or user not found.');
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('An unexpected error occurred.');
        }
    }
}
