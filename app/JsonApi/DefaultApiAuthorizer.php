<?php

namespace App\JsonApi;

use CloudCreativity\LaravelJsonApi\Auth\AbstractAuthorizer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class DefaultApiAuthorizer extends AbstractAuthorizer
{
    /**
     * Authorize a resource index request.
     *
     * @param string $type
     *      the domain record type.
     * @param Request $request
     *      the inbound request.
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function index($type, $request): void
    {
        // All authenticated users may view all
    }


    /**
     * Authorize a resource create request.
     *
     * @param string $type
     *      the domain record type.
     * @param Request $request
     *      the inbound request.
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function create($type, $request): void
    {
        // All authenticated users may create
    }


    /**
     * Authorize a resource read request.
     *
     * @param object $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function read($record, $request): void
    {
        // All authenticated users may view
    }


    /**
     * Authorize a resource update request.
     *
     * @param object $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function update($record, $request): void
    {
        // Hand off to policy
        $this->authorize('update', $record);
    }
    

    /**
     * Authorize a resource read request.
     *
     * @param object $record
     *      the domain record.
     * @param Request $request
     *      the inbound request.
     * @throws AuthenticationException|AuthorizationException
     *      if the request is not authorized.
     */
    public function delete($record, $request): void
    {
        // Hand off to policy
        $this->authorize('delete', $record);
    }
}
