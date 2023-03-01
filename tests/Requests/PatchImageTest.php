<?php

use BenBjurstrom\CloudflareImages\Data\ImageData;
use BenBjurstrom\CloudflareImages\Requests\PatchImage;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('patch image endpoint', function () {
    $mockClient = new MockClient([
        PatchImage::class => MockResponse::fixture('patchImage'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $metadata = [
        'name' => 'Example2.jpg',
        'description' => 'This is also example image',
        'user_id' => '123',
    ];
    $id = '00000000-0000-0000-0000-000000000000';
    $request = new PatchImage(id: $id, metadata: $metadata);
    $response = $connector->send($request);

    /* @var ImageData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000')
        ->and($data->variants[0])
        ->toBe('https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000/public')
        ->and($data->metadata['name'])
        ->toBe('Example2.jpg')
        ->and($data->metadata['user_id'])
        ->toBe('123');
});
