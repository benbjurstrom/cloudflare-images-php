<?php

namespace BenBjurstrom\CloudflareImages;

use BenBjurstrom\CloudflareImages\Data\VariantsData;
use BenBjurstrom\CloudflareImages\Requests\GetVariants;
use Exception;

class VariantResource extends Resource
{
    public function all(): VariantsData
    {
        $request = new GetVariants();
        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof VariantsData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }
}
