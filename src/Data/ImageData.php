<?php

namespace BenBjurstrom\CloudflareImages\Data;

use Exception;
use Saloon\Contracts\Response;

final class ImageData
{
    /**
     * @param  array<int, string>  $variants
     * @param  array<int, string>  $metadata
     */
    public function __construct(
        public string $id,
        public ?string $filename,
        public string $uploaded,
        public bool $requireSignedURLs,
        public array $variants,
        public array $metadata,
        public bool $isDraft,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json('result');
        if (! is_array($data)) {
            throw new Exception('Invalid response');
        }

        return new self(
            id: $data['id'],
            filename: $data['filename'],
            uploaded: $data['uploaded'],
            requireSignedURLs: $data['requireSignedURLs'],
            variants: $data['variants'],
            metadata: $data['meta'] ?? [],
            isDraft: $data['draft'] ?? false,
        );
    }
}
