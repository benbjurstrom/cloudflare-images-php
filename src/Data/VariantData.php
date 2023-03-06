<?php

namespace BenBjurstrom\CloudflareImages\Data;

final class VariantData
{
    public function __construct(
        public string $id,
        public string $fit,
        public int $width,
        public int $height,
        public string $metadata,
        public bool $neverRequireSignedURLs,
    ) {
    }
}
