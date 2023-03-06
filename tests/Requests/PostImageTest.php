<?php

use BenBjurstrom\CloudflareImages\Data\ImageData;
use BenBjurstrom\CloudflareImages\Requests\PostImage;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('post image endpoint', function () {
    $mockClient = new MockClient([
        PostImage::class => MockResponse::fixture('postImage'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $url = 'https://upload.wikimedia.org/wikipedia/en/a/a9/Example.jpg';
    $request = new PostImage();
    $request->body()->add(
        name: 'url',
        contents: $url,
    );
    $response = $connector->send($request);

    /* @var ImageData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000')
        ->and($data->variants[0])
        ->toBe('https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000/public');
});
