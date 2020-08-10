<?php

namespace App\JsonApi\Users;

use App\User;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\HasMany;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
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
        parent::__construct(new User(), $paging);
    }


    /**
     * @param Builder $query
     * @param Collection $filters
     */
    protected function filter($query, Collection $filters): void
    {
        $this->filterWithScopes($query, $filters);
    }


    protected function tasks(): HasMany
    {
        return $this->hasMany();
    }


    protected function tags(): HasMany
    {
        return $this->hasMany();
    }
}
