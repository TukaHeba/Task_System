<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The name of the table associated with the model.
     */
    protected $table = 'task_table';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'task_id';

    /**
     * Make the primary key automatically increment 
     */
    public $incrementing = true;

    /**
     * The data type of the primary key.
     */
    protected $keyType = 'int';
    /**
     * The number of models to return per page.
     */
    protected $perPage = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'assigned_to',
        'created_by',
    ];

    /**
     * The attributes that are not mass assignable.
     * I keep it empty because there is no attributes need to be guarded
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'due_date' => 'datetime',
        'priority' => 'string',
        'status' => 'string',
        'assigned_to' => 'integer',
    ];

    /**
     * Customizing timestamps fields.
     */
    public $timestamps = true;
    public const CREATED_AT = 'created_on';
    public const UPDATED_AT = 'updated_on';

    /**
     * Accessor for formatted due date.
     * 
     * @return string
     */
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    /**
     * Get the user that is assigned to the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
