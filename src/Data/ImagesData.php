<?php

namespace BenBjurstrom\CloudflareImages\Data;

use Saloon\Contracts\Response;

final class ImagesData
{
    /**
     * @param  array<int, ImageData>  $images
     */
    public function __construct(
        public array $images,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json('result');
        if (! is_array($data)) {
            throw new \Exception('Invalid response');
        }

        $imgArr = [];
        foreach ($data['images'] as $image) {
            $imgArr[] = new ImageData(
                id: $image['id'],
                filename: $image['filename'],
                uploaded: $image['uploaded'],
                requireSignedURLs: $image['requireSignedURLs'],
                variants: $image['variants'],
                metadata: $image['meta'] ?? [],
            );
        }

        return new ImagesData(
            images: $imgArr,
        );
    }
}
