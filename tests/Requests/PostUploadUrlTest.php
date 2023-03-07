<?php

use BenBjurstrom\CloudflareImages\Data\UploadUrlData;
use BenBjurstrom\CloudflareImages\Requests\PostUploadUrl;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('post upload url endpoint', function () {
    $mockClient = new MockClient([
        PostUploadUrl::class => MockResponse::fixture('postUploadUrl'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $metadata = json_encode([
        'name' => 'Example.jpg',
        'description' => 'This is an example image',
    ]);
    $request = new PostUploadUrl();
    $request->body()->add(
        name: 'metadata',
        contents: json_encode($metadata, JSON_THROW_ON_ERROR)
    );
    $response = $connector->send($request);

    /* @var UploadUrlData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000')
        ->and($data->uploadUrl)
        ->toBe('https://upload.imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000');
});
