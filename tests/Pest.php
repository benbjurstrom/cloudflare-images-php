<?php

use BenBjurstrom\CloudflareImages\CloudflareImages;

function getConnector(): CloudflareImages
{
    $apiToken = getenv('CLOUDFLARE_IMAGES_API_TOKEN');
    $accountId = getenv('CLOUDFLARE_IMAGES_ACCOUNT_ID');

    return new CloudflareImages(
        apiToken: $apiToken ? $apiToken : '',
        accountId: $accountId ? $accountId : ''
    );
}
