<?php

namespace App\JsonApi\Tasks;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'tasks';


    /**
     * @param \App\Task $resource
     *      the domain record being serialized.
     */
    public function getId($resource): string
    {
        return (string) $resource->getRouteKey();
    }


    /**
     * @param \App\Task $resource
     *      the domain record being serialized.
     */
    public function getAttributes($resource): array
    {
        return [
            'body' => $resource->body,
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
            'author' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['author']),
                self::DATA => function () use ($resource) {
                    return $resource->author;
                },
            ],
            'editor' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['editor']),
                self::DATA => function () use ($resource) {
                    return $resource->editor;
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
        return ['tags'];
    }
}
