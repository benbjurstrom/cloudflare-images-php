# Cloudflare Images PHP client
This is a framework-agnostic PHP client for [Cloudflare Images](https://developers.cloudflare.com/images/cloudflare-images/) built on the amazing [Saloon v2](https://docs.saloon.dev/) 🤠 library.

## Quick start 🎉

Install with composer.

```bash
composer require benbjurstrom/cloudflare-images-php
```

Instantiate the connector with your Cloudflare API token and Account ID. Pick one of the included request objects and pass it to the connector's `send` method. Finally, exchange the response object for one of the included Data Transfer Objects.
```php
use BenBjurstrom\CloudflareImages\CloudflareImages;
use BenBjurstrom\CloudflareImages\Data\UploadUrlData;
use BenBjurstrom\CloudflareImages\Requests\PostUploadUrl;
...
$api = new CloudflareImages($apiToken, $accountId)

$api->getPage(1, 20);
$api->get($id);
$api->delete($id);
$api->update($id, ?$metadata, ?$private)
$api->create($url, ?$metadata, ?$private);    
$api->uploadUrl($metadata, ?$private, ?$customId);

$connector = new CloudflareImages($apiToken, $accountId);
$request   = new PostUploadUrl();
$response  = $connector->send($request);

/* @var UploadUrlData $data */
$data = $response->dtoOrFail();

$data->uploadUrl; // https://upload.imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000"
```

For more details on the lifecycle check out Saloon's documentation on [Connectors](https://docs.saloon.dev/the-basics/connectors), [Requests](https://docs.saloon.dev/the-basics/requests), [Responses](https://docs.saloon.dev/the-basics/responses) and [DTOs](https://docs.saloon.dev/the-basics/data-transfer-objects).

## Other Request Object Examples
### GetImage
Fetches image details by ID.
```php
use BenBjurstrom\CloudflareImages\CloudflareImages;
use BenBjurstrom\CloudflareImages\Data\ImageData
use BenBjurstrom\CloudflareImages\Requests\GetImage;
...

$connector = new CloudflareImages($apiToken, $accountId);
$request   = new GetImage($id);
$response  = $connector->send($request);

/* @var ImageData $data */
$data = $response->dtoOrFail();

// See the ImageData class for all available properties
$data->variants[0]; // https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000/public

// For private images call the ImageData::signUrls method with your signing key
$data->signUrls($signingKey)
$data->variants[0]; // https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000/public?sig=8217cb17667a1f1af8ed722124d7a5da9543df9e3040a51f3de6e3023812ab3
```
### GetImages
Fetches a list of images.
```php
```
### PostImage
Creates a new image from a given URL.
```php
```
### PatchImage
Updates an image's details.
```php
```
### DeleteImage
Deletes and image by ID.
```php
```
