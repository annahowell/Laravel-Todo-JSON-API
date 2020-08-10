<?php

namespace App\JsonApi\Tags;

use App\Tag;
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
        parent::__construct(new Tag(), $paging);
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
     * @param Tag $tag
     */
    protected function creating(Tag $tag): void
    {
        // Set the creator as the currently logged in user
        $tag->created_by = auth()->id();
    }
    

    protected function tasks(): HasMany
    {
        return $this->hasMany();
    }
}
