# Cloudflare Images PHP client
This is a framework-agnostic PHP client for [Cloudflare Images](https://developers.cloudflare.com/images/cloudflare-images/) built on the amazing [Saloon v2](https://docs.saloon.dev/) 🤠 library.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/benbjurstrom/cloudflare-images-php.svg?style=flat-square)](https://packagist.org/packages/benbjurstrom/cloudflare-images-php)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/benbjurstrom/cloudflare-images-php/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/benbjurstrom/cloudflare-images-php/actions?query=workflow%3tests+branch%3Amain)

## Table of contents
- [Quick Start](https://github.com/benbjurstrom/cloudflare-images-php#-quick-start)
- [Using with Laravel](https://github.com/benbjurstrom/cloudflare-images-php#using-with-laravel)
- [Response Data](https://github.com/benbjurstrom/cloudflare-images-php#response-data)
- [Image Metadata](https://github.com/benbjurstrom/cloudflare-images-php#image-metadata)
- [Private Images](https://github.com/benbjurstrom/cloudflare-images-php#response-data)
- [Custom IDs](https://github.com/benbjurstrom/cloudflare-images-php#custom-ids)
- [Image Methods](https://github.com/benbjurstrom/cloudflare-images-php#available-image-methods)
  - [get](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#get)
  - [list](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#list)
  - [update](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#update)
  - [delete](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#delete)
  - [create](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#create)
  - [createFromUrl](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#createfromurl)
  - [getUploadUrl](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#getuploadurl)
- [Variant Methods](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#variant-methods)
  - [list](https://github.com/benbjurstrom/cloudflare-images-php/tree/9-rename-methods#list-1)

## 🚀 Quick start

Install with composer.

```bash
composer require benbjurstrom/cloudflare-images-php
```

Use your Cloudflare API token and Account ID to create a new api instance.
```php
use BenBjurstrom\CloudflareImages\CloudflareImages;
...

$api = new CloudflareImages(
    apiToken: $_ENV['CLOUDFLARE_IMAGES_API_TOKEN'],
    accountId: $_ENV['CLOUDFLARE_IMAGES_ACCOUNT_ID']
);
```
Then use the api instance to get details about an existing image such as its file name, metadata, or available variants.
```php
$id = '2cdc28f0-017a-49c4-9ed7-87056c83901'
$data = $api->images()->get($id);
$data->variants[0]; // https://imagedelivery.net/Vi7wi5KSItxGFsWRG2Us6Q/2cdc28f0-017a-49c4-9ed7-87056c83901/public
```

Or use the api to upload a new image from an image string.
```php
$fileName = 'example.jpg';
$file = file_get_contents($fileName);

$data = $api->images()->create($file, $fileName);
$data->id; // 2cdc28f0-017a-49c4-9ed7-87056c83901
```

Or generate a one time upload url that lets your users upload images directly to cloudflare without exposing your api key.
```php
$data = $api->images()->getUploadUrl();
$data->uploadUrl; // https://upload.imagedelivery.net/Vi7wi5KSItxGFsWRG2Us6Q/d63a6953-12b9-4d89-b8d6-083c86289b93
```
You can find more information about direct creator uploads in the [Cloudflare Docs](https://developers.cloudflare.com/images/cloudflare-images/upload-images/direct-creator-upload/).

## Using with Laravel
It's easy to use this package in Laravel. Begin by adding your credentials to your services config file.
```php
// config/services.php
'cloudflare' => [
    'api_token' => env('CLOUDFLARE_IMAGES_API_TOKEN'),
    'account_id' => env('CLOUDFLARE_IMAGES_ACCOUNT_ID'),
    'signing_key' => env('CLOUDFLARE_IMAGES_SIGNING_KEY'),
],
```
Bind the `CloudflareImages` class in a service provider.
```php
// app/Providers/AppServiceProvider.php
public function register()
{
    $this->app->bind(CloudflareImages::class, function () {
        return new CloudflareImages(
            apiToken: config('services.cloudflare.api_token'),
            accountId: config('services.cloudflare.account_id'),
            signingKey: config('services.cloudflare.signing_key'),
        );
    });
}
````
And use anywhere in your application.
```php
$data = app(CloudflareImages::class)->images()->get($id);
```

It's also really easy to test your integration using Saloon's amazing [response recording](https://docs.saloon.dev/testing/recording-requests#fixture-path).
```php
use Saloon\Laravel\Saloon; // composer require sammyjo20/saloon-laravel "^2.0"
...
Saloon::fake([
    MockResponse::fixture('getImage'),
]);

$id = 'a74a4313-a51d-4837-b5c1-73e4c562ff00';

// The initial request will check if a fixture called "getImage" 
// exists. Because it doesn't exist yet, the real request will be
// sent and the response will be recorded to tests/Fixtures/Saloon/getImage.json.
$imgData = app(CloudflareImages::class)->images()->get($id);

// However, the next time the request is made, the fixture will 
// exist, and Saloon will not make the request again.
$imgData = app(CloudflareImages::class)->images()->get();
```

## Response Data
All responses are returned as data objects. Detailed information can be found by inspecting the following class properties:

* [ImageData](https://github.com/benbjurstrom/cloudflare-images-php/blob/main/src/Data/ImageData.php)
* [ImagesData](https://github.com/benbjurstrom/cloudflare-images-php/blob/main/src/Data/ImagesData.php)
* [UploadUrlData](https://github.com/benbjurstrom/cloudflare-images-php/blob/main/src/Data/UploadUrlData.php)
* [VariantData](https://github.com/benbjurstrom/cloudflare-images-php/blob/main/src/Data/VariantData.php)

## Image Metadata
Cloudflare allows you attach a modifiable key-value store to your images. To attach metadata to your image chain `withMetadata($metadata)` onto your api instance before calling the `create`, `createFromUrl`, `update`, or `getUploadUrl` methods. For example:

```php
$url = 'https://en.wikipedia.org/wiki/File:Example.jpg'
$api->images()->withMetadata(['user_id' => '123'])->createFromUrl($url);
```

## Private Images
Cloudflare allows you to configure an image to only be accessible with a signed URL token. To make an image private chain `private(true)` onto your api instance before calling the `create`, `createFromUrl`, `update`, or `getUploadUrl` methods. For example:

```php
$api->images()->private(true)->getUploadUrl();
```

To generate signatures instantiate your api with the optional signing key parameter and then pass the url you want to sign to the `signUrl` method.
```php
$api = new CloudflareImages(
    ...
    $signingKey: $_ENV['CLOUDFLARE_IMAGES_SIGNING_KEY']
);

$url = 'https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000/public';
$api->signUrl($url); // https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000/public?sig=8217cb17667a1f1af8ed722124d7a5da9543df9e3040a51f3de6e3023812ab3
```

You can find more information about serving private images in the [Cloudflare documentation](https://developers.cloudflare.com/images/cloudflare-images/signing-images/).

## Custom IDs
Cloudflare allows you to configure a custom identifier if you wish. To do so chain `withCustomId($id)` onto your api instance before calling the `create`, `createFromUrl`, or `getUploadUrl` methods. For example:

```php
$api->images()->withCustomId('test/image123')->create($file, $fileName);
$data->id; // test/image123
```

Note that images with a custom ID cannot be made private. You can find more information about custom ids in the [Cloudflare documentation](https://developers.cloudflare.com/images/cloudflare-images/upload-images/custom-id/).

## Available Image Methods
### get()
Use to get details about an image such as its file name, metadata, and available variants. Returns an ImageData object.
```php
use BenBjurstrom\CloudflareImages\Data\ImageData;
...
$id = '2cdc28f0-017a-49c4-9ed7-87056c83901'
/* @var ImageData $data */
$data = $api->images()->get($id);
$data->variants[0]; // https://imagedelivery.net/Vi7wi5KSItxGFsWRG2Us6Q/2cdc28f0-017a-49c4-9ed7-87056c83901/public
```

### list()
Use to get a paginated list of images. Returns an ImagesData object.
```php
use BenBjurstrom\CloudflareImages\Data\ImagesData
...

/* @var ImagesData $data */
$data = $api->images()->list(
    page: 1, // optional
    perPage: 25, // optional
);

$data->images[0]->id; // 00000000-0000-0000-0000-000000000000

```
### create()
Use to upload an image from an image string. Returns an ImageData object.
```php
use BenBjurstrom\CloudflareImages\Data\ImageData;
...
$fileName = 'example.jpg';
$file = file_get_contents($fileName);

/* @var ImageData $data */
$data = $api->images()
    ->private(false) // optional
    ->withCustomId('test/image123') // optional
    ->withMetadata(['user_id' => '123']) // optional
    ->create($file, $fileName);
$data->id; // test/image123
```

### createFromUrl()
Use to add an image to Cloudflare from a given url. Returns an ImageData object.
```php
use BenBjurstrom\CloudflareImages\Data\ImageData
...

$url = 'https://en.wikipedia.org/wiki/File:Example.jpg'

/* @var ImageData $data */
$data = $api->images()
    ->private(false) // optional
    ->withMetadata(['user_id' => '123']) // optional
    ->createFromUrl($id);
```
### update()
Use to update an image's metadata or privacy setting. Returns an ImageData object.

⚠️ WARNING - Modifying an image's privacy setting will change the image's identifier.

```php
use BenBjurstrom\CloudflareImages\Data\ImageData
...

$id = 'd63a6953-12b9-4d89-b8d6-083c86289b93'

/* @var ImageData $data */
$data = $api->images()
    ->private(false) // optional
    ->withMetadata(['user_id' => '123']) // optional
    ->update($id);

$data->id; // Contains a new id if the privacy setting was changed. If you are tracking IDs be sure to update your database.
```
### delete()
Use to delete an image. Returns a boolean.
```php
$id = 'd63a6953-12b9-4d89-b8d6-083c86289b93'
$data = $api->images()->delete($id);
$data // true
```

### getUploadUrl()
Use to generate a one time upload url that lets your users upload images directly to cloudflare without exposing your api key. Returns an UploadUrlData object.
```php
use BenBjurstrom\CloudflareImages\Data\UploadUrlData;
...

/* @var UploadUrlData $data */
$data = $api->images()
    ->withMetadata(['user_id' => '123']) // optional
    ->getUploadUrl();
$data->uploadUrl; // https://upload.imagedelivery.net/Vi7wi5KSItxGFsWRG2Us6Q/d63a6953-12b9-4d89-b8d6-083c86289b93
```

You can find more information about direct creator uploads in the [Cloudflare Docs](https://developers.cloudflare.com/images/cloudflare-images/upload-images/direct-creator-upload/).

## Variant Methods
### list()
Use to get a list of all variants. Returns a VariantsData object.
```php
use BenBjurstrom\CloudflareImages\Data\VariantsData
...

/* @var VariantsData $data */
$data = $api->variants()->all()
$data->variants[0]->id; // public
$data->variants[0]->width; // 1366
$data->variants[0]->height; // 768
```
