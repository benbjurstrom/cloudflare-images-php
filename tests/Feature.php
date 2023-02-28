<?php


use BenBjurstrom\CloudflareImages\Example;

it('foo', function () {
    $example = new Example();

    $result = $example->foo();

    expect($result)->toBe('bar');
});
