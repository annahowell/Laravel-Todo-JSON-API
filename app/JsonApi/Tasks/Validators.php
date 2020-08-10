<?php

namespace App\JsonApi\Tasks;

use CloudCreativity\LaravelJsonApi\Rules\HasMany;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'author',
        'editor',
        'tags',
    ];


    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [];


    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = [];


    /**
     * Get resource validation rules.
     *
     * @param mixed|null $record
     *      the record being updated, or null if creating a resource.
     */
    protected function rules($record = null): array
    {
        return [
            'body' => 'required|string|min:1',
            'tags' => new HasMany('tags'),
        ];
    }


    /**
     * Get query parameter validation rules.
     *
     */
    protected function queryRules(): array
    {
        return [
            //
        ];
    }
}
