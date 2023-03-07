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

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000');
});

test('get image endpoint draft', function () {
    $mockClient = new MockClient([
        GetImage::class => MockResponse::fixture('getImageDraft'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $id = '00000000-0000-0000-0000-000000000000';
    $request = new GetImage(id: $id);
    $response = $connector->send($request);

    /* @var ImageData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->isDraft)
        ->toBeTrue();
});
