<?php

namespace BenBjurstrom\CloudflareImages\Requests;

use BenBjurstrom\CloudflareImages\Data\VariantsData;
use Saloon\Contracts\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetVariants extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v1/variants';
    }

    public function createDtoFromResponse(Response $response): VariantsData
    {
        return VariantsData::fromResponse($response);
    }
}
