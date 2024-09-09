<?php

namespace App\Http\Requests;

use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * Only admins and managers can access this action
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role !== 'user';
    }

    /**
     * Prepare the data for validation.
     * This method automatically sets or formats specific fields before validation occurs.
     * 
     * Capitalize the first letter of each word in title and trim white spaces if provided
     * Make created_by field to set as the current authenticated user's ID.
     * Make created_on field to set as current timestamp.
     * Make updated_on null because it is the first creation of the task
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'title' => $this->title ? ucwords(trim($this->title)) : null,
            'created_by' => $this->user()->id,
            'created_on' => now(),
            'updated_on' => null,
        ]);
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
            'created_by' => 'exists:users,id',
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
