<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    protected $fillable = ['title', 'color'];

    /**
     * Get the tasks with this tag
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany('App\Task', 'task_tags')->withTimestamps();
    }
    

    /**
     * Get the user that owns the tag. Used to ensure only the user who made the
     * tag can amend it; not exposed to the API
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
