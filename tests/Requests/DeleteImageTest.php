<?php

use BenBjurstrom\CloudflareImages\Requests\DeleteImage;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('delete images endpoint', function () {
    $mockClient = new MockClient([
        DeleteImage::class => MockResponse::fixture('deleteImage'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $id = '00000000-0000-0000-0000-000000000000';
    $request = new DeleteImage(id: $id);
    $response = $connector->send($request);

    expect($response->ok())->toBeTrue();
});
