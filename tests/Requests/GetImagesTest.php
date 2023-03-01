<?php

use BenBjurstrom\CloudflareImages\Data\ImagesData;
use BenBjurstrom\CloudflareImages\Requests\GetImages;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('get images endpoint', function () {
    $mockClient = new MockClient([
        GetImages::class => MockResponse::fixture('getImages'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $request = new GetImages(page: 1, perPage: 1);
    $response = $connector->send($request);

    /* @var ImagesData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->images[0]->id)
        ->toBe('00000000-0000-0000-0000-000000000001')
        ->and($data->images[0]->variants[0])
        ->toBe('https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000001/public');
});
