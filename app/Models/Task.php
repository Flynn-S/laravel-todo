<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['task_name', 'description', 'isCompleted'];

    protected $dates = ['completed_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['isCompleted'] = false;
    }
}
