<?php

use BenBjurstrom\CloudflareImages\Data\ImagesData;
use BenBjurstrom\CloudflareImages\Requests\GetVariants;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('get variants endpoint', function () {
    $mockClient = new MockClient([
        GetVariants::class => MockResponse::fixture('getVariants'),
    ]);

    $connector = getConnector();
    $connector->withMockClient($mockClient);

    $request = new GetVariants();
    $response = $connector->send($request);

    /* @var ImagesData $data */
    $data = $response->dtoOrFail();

    expect($response->ok())
        ->toBeTrue()
        ->and($data->variants[0]->id)
        ->toBe('public');
});
