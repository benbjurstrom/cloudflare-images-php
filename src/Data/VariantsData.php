<?php

namespace BenBjurstrom\CloudflareImages\Data;

use Saloon\Contracts\Response;

final class VariantsData
{
    /**
     * @param  array<int, VariantData>  $variants
     */
    public function __construct(
        public array $variants,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json('result');
        if (! is_array($data)) {
            throw new \Exception('Invalid response');
        }

        $arr = [];
        foreach ($data['variants'] as $variant) {
            $arr[] = new VariantData(
                id: $variant['id'],
                fit: $variant['options']['fit'],
                width: $variant['options']['width'],
                height: $variant['options']['height'],
                metadata: $variant['options']['metadata'],
                neverRequireSignedURLs: $variant['neverRequireSignedURLs'],
            );
        }

        return new VariantsData(
            variants: $arr,
        );
    }
}
