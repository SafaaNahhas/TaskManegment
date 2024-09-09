<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
 /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';
/**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'priority', 'due_date', 'status', 'assigned_to','created_by'];
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

 /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_on';
 /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_on';
 /**
     * Mutator for setting the due date.
     * It converts the input date format to the desired Carbon instance.
     *
     * @param string $value
     * @return void
     */
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat('d-m-Y H:i', $value);
    }

    /**
     * Accessor for retrieving the due date.
     * It converts the stored date into the desired format.
     *
     * @param string $value
     * @return string
     */
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
 /**
     * Accessor for retrieving the created_on date.
     *
     * @param string $value
     * @return string
     */
    public function getCreatedOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
  /**
     * Accessor for retrieving the updated_on date.
     *
     * @param string $value
     * @return string
     */
    public function getUpdatedOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

 /**
     * Scope a query to only include tasks of a given priority.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
 /**
     * Scope a query to only include tasks of a given status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
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
}
