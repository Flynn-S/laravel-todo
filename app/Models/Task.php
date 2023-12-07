<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id','task_name', 'description', 'isCompleted'];

    protected $dates = ['completed_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['isCompleted'] = false;
    }

    // public function dependancies(): HasMany
    // {
    //     return $this->hasMany(Task::class, 'id');
    // }
    // public function dependancies(): HasMany
    // {
    //     return $this->hasMany(Task::class, 'parent_id');
    // }

    public function dependencies():BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'dependency_id');
    }

    public function dependants():BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'dependency_id', 'task_id');
    }
}
