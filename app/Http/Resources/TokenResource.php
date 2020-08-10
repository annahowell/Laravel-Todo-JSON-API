<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($this->token->expires_at)->toDateTimeString()
        ];
    }
}
