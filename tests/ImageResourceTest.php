<?php

use BenBjurstrom\CloudflareImages\Requests\DeleteImage;
use BenBjurstrom\CloudflareImages\Requests\GetImage;
use BenBjurstrom\CloudflareImages\Requests\GetImages;
use BenBjurstrom\CloudflareImages\Requests\PatchImage;
use BenBjurstrom\CloudflareImages\Requests\PostImage;
use BenBjurstrom\CloudflareImages\Requests\PostUploadUrl;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('image getList', function () {
    $mockClient = new MockClient([
        GetImages::class => MockResponse::fixture('getImages'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $data = $connector->images()->list();

    expect($data->images[0]->id)
        ->toBe('00000000-0000-0000-0000-000000000001');
});

test('image get', function () {
    $mockClient = new MockClient([
        GetImage::class => MockResponse::fixture('getImage'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $data = $connector->images()->get('00000000-0000-0000-0000-000000000000');

    expect($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000');
});

test('image delete', function () {
    $mockClient = new MockClient([
        DeleteImage::class => MockResponse::fixture('deleteImage'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $data = $connector->images()->delete('00000000-0000-0000-0000-000000000000');

    expect($data)->toBeTrue();
});

test('image update', function () {
    $mockClient = new MockClient([
        PatchImage::class => MockResponse::fixture('patchImage'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $data = $connector
        ->images()
        ->private()
        ->withMetadata([
            'name' => 'test',
        ])
        ->update('00000000-0000-0000-0000-000000000000');

    expect($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000');
});

test('image uploadFromUrl', function () {
    $mockClient = new MockClient([
        PostImage::class => MockResponse::fixture('postImage'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $data = $connector->images()->uploadFromUrl('https://example.com/image.jpg');

    expect($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000');
});

test('image upload url', function () {
    $mockClient = new MockClient([
        PostUploadUrl::class => MockResponse::fixture('postUploadUrl'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $data = $connector
        ->images()
        ->private()
        ->withMetadata([
            'name' => 'test',
        ])
        ->getUploadUrl();

    expect($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000');
});

test('image uploadFromString', function () {
    $mockClient = new MockClient([
        PostImage::class => MockResponse::fixture('postImageFromString'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAgAAAAIAQMAAAD+wSzIAAAABlBMVEX///+/v7+jQ3Y5AAAADklEQVQI12P4AIX8EAgALgAD/aNpbtEAAAAASUVORK5CYII';

    $data = $connector
        ->images()
        ->upload(base64_decode($base64), 'test.png');

    expect($data->id)
        ->toBe('00000000-0000-0000-0000-000000000000');
});
