<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = ['body'];

    /**
     * Get the original author of the task
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo('App\User', 'created_by');
    }


    /**
     * Get the most recent editor of the task.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo('App\User', 'updated_by');
    }


    /**
     * Get the tags for the post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany('App\Tag', 'task_tags')->withTimestamps();
    }
}
