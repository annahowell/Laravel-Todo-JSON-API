<?php

namespace App\JsonApi\Users;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'users';


    /**
     * @param \App\User $resource
     *      the domain record being serialized.
     */
    public function getId($resource): string
    {
        return (string) $resource->getRouteKey();
    }


    /**
     * @param \App\User $resource
     *      the domain record being serialized.
     */
    public function getAttributes($resource): array
    {
        return [
            'display-name' => $resource->displayname,
            'created-at' => $resource->created_at->toAtomString(),
            'updated-at' => $resource->updated_at->toAtomString(),
        ];
    }


    /**
     * @param object $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships): array
    {
        return [
            'tasks' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['tasks']),
                self::DATA => function () use ($resource) {
                    return $resource->tasks;
                },
            ],
            'tags' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['tags']),
                self::DATA => function () use ($resource) {
                    return $resource->tags;
                },
            ],
        ];
    }


    public function getIncludePaths(): array
    {
        return ['tasks', 'tags'];
    }
}
