<?php

use BenBjurstrom\CloudflareImages\Data\ImageData;
use BenBjurstrom\CloudflareImages\Requests\GetImage;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('get image endpoint', function () {
    $mockClient = new MockClient([
        GetImage::class => MockResponse::fixture('getImage'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $id = '00000000-0000-0000-0000-000000000000';
    $request = new GetImage(id: $id);
    $response = $connector->send($request);

    /* @var ImageData $data */
    $data = $response->dtoOrFail();

    $signingKey = getenv('CLOUDFLARE_IMAGES_SIGNING_KEY');
    $data->signUrls($signingKey);

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000')
        ->and($data->variants[0])
        ->toContain('https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000/public?sig=');
});


