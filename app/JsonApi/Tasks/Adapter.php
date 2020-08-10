<?php

namespace App\JsonApi\Tasks;

use App\Task;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\HasMany;
use CloudCreativity\LaravelJsonApi\Eloquent\HasOne;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    protected $defaultWith = ['tags'];

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];


    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [];



    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new Task(), $paging);
    }


    /**
     * @param Builder $query
     * @param Collection $filters
     */
    protected function filter($query, Collection $filters): void
    {
        $this->filterWithScopes($query, $filters);
    }


    /**
     * @param Task $task
     */
    protected function creating(Task $task): void
    {
        $task->created_by = auth()->id();
        $task->updated_by = auth()->id();
    }


    /**
     * @param Task $task
     */
    protected function updating(Task $task): void
    {
        $task->updated_by = auth()->id();
    }


    protected function author(): HasOne
    {
        return $this->hasOne();
    }


    protected function editor(): HasOne
    {
        return $this->hasOne();
    }
    

    protected function tags(): HasMany
    {
        return $this->hasMany();
    }
}
