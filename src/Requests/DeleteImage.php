<?php

namespace BenBjurstrom\CloudflareImages\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteImage extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return sprintf('/v1/%s', $this->id);
    }
}
