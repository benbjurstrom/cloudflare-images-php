<?php

namespace BenBjurstrom\CloudflareImages;

use Saloon\Contracts\Connector;

class Resource
{
    public function __construct(protected Connector $connector)
    {
        //
    }
}
