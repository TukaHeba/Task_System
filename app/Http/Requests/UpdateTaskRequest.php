<?php

namespace App\Http\Requests;

use App\Models\Task;
use App\Services\ApiResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * This method automatically sets or formats specific fields before validation occurs.
     * 
     * Retrieve the task_id from the route parameters, then get the task details
     * When manager update assigned_to value will be kept
     * When user trying to update onlt status value can be updated
     * 
     * For all roles: 
     * original created_by and created_on values will kept
     * updated_on will take  current timestamp 
     * @return void
     */
    public function prepareForValidation()
    {
        $user = $this->user();
        $taskId = $this->route('id');
        $task = Task::find($taskId);

        $this->merge([
            'updated_on' => now(),
            'created_by' => $this->task->created_by,
            'created_on' => $this->task->created_on,
        ]);

        if ($user->role === 'admin') {
            $this->merge([
                'title' => $this->title ? ucwords(trim($this->title)) : null,
            ]);
        }

        if ($user->role === 'manager') {
            $this->merge([
                'assigned_to' => $this->task->assigned_to,
            ]);
        }

        if ($user->role === 'user') {
            $this->replace([
                'status' => $this->input('status', $this->task->status),
                'updated_on' => now(),
                'created_by' => $this->task->created_by,
                'created_on' => $this->task->created_on,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * I do not add created_by, created_on, and updated_on because it will be added by prepareForValidation
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:1000|min:10',
            'priority' => 'required|string|in:low,medium,high',
            'due_date' => 'required|after_or_equal:today',
            'status' => 'string|in:pending,in_progress,completed,failed',
            'assigned_to' => 'nullable|exists:users,id',
            // 'created_by' => 'exists:users,id',
            // 'created_on' => 'date',
            'updated_on' => 'date',
        ];
    }
    /**
     * Get the custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => 'task title',
            'description' => 'task description',
            'priority' => 'task priority',
            'due_date' => 'task due date',
            'status' => 'task status',
            'assigned_to' => 'assigned user',
            'created_by' => 'task creator',
            'created_on' => 'created on date',
            'updated_on' => 'updated on date',
        ];
    }

    /**
     * Get the custom messages for the validator.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'exists' => 'The :attribute does not exist.',
            'string' => 'The :attribute must be a string.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'min' => 'The :attribute must be at least :min characters.',
            'in' => 'The :attribute must be one of the following: :values.',
            'date' => 'The :attribute must be a valid date.',
            'due_date.after_or_equal' => 'The :attribute must be a date after or equal to :date.',
        ];
    }

    /**
     * Handle validation errors and throw an exception.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator The validation instance.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(
            ApiResponseService::error('An error occurred on the server.', 500)
        );
    }
}
