<?php

namespace BenBjurstrom\CloudflareImages\Requests;

use BenBjurstrom\CloudflareImages\Data\ImagesData;
use Saloon\Contracts\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetImages extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int $perPage,
        protected int $page
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/v1';
    }

    protected function defaultQuery(): array
    {
        return [
            'per_page' => $this->perPage,
            'page' => $this->page,
        ];
    }

    public function createDtoFromResponse(Response $response): ImagesData
    {
        return ImagesData::fromResponse($response);
    }
}
