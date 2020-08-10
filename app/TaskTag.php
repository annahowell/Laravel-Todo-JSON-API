<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskTag extends Model
{
    protected $table = 'task_tags';

    protected $fillable = ['task_id', 'tag_id'];
}
