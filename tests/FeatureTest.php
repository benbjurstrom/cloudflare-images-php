<?php

use BenBjurstrom\CloudflareImages\CloudflareImages;

test('image delete', function () {
    $apiToken = getenv('CLOUDFLARE_IMAGES_API_TOKEN');
    $accountId = getenv('CLOUDFLARE_IMAGES_ACCOUNT_ID');
    $signingKey = getenv('CLOUDFLARE_IMAGES_SIGNING_KEY');

    $api = new CloudflareImages(
        apiToken: $apiToken ? $apiToken : '',
        accountId: $accountId ? $accountId : '',
        signingKey: $signingKey ? $signingKey : ''
    );

    $data = $api->images()->get('058aae72-20b6-4eb5-c75c-dd0ca9e87601');

    $signed = $api->signUrl('https://imagedelivery.net/tlMUIgxBCDMUcIu6pbnihg/058aae72-20b6-4eb5-c75c-dd0ca9e87601/public');

    // dd($signed);
})->skip();
