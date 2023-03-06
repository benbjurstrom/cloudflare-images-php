# Cloudflare Images PHP client
This is a framework-agnostic PHP client for [Cloudflare Images](https://developers.cloudflare.com/images/cloudflare-images/) built on the amazing [Saloon v2](https://docs.saloon.dev/) 🤠 library.

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
Then use the api instance to get details about an image such as its file name, metadata, and available variants.
```php
use BenBjurstrom\CloudflareImages\Data\ImageData;
...

$id = '00000000-0000-0000-0000-000000000000'
/* @var ImageData $data */
$data = $api->images()->get($id);
$data->variants[0]; // https://imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000/public
```

Or upload from an image string.
```php
use BenBjurstrom\CloudflareImages\Data\UploadUrlData;
...
$fileName = 'example.jpg';
$file = file_get_contents($fileName);

/* @var ImageData $data */
$data = $api->images()
    ->private(false) // optional
    ->withMetadata(['user_id' => '123']) // optional
    ->upload($file, $fileName);
$data->id; // 00000000-0000-0000-0000-000000000000
```

Or generate a one time upload url that lets your users upload images directly to cloudflare without exposing your api key.
```php
use BenBjurstrom\CloudflareImages\Data\UploadUrlData;
...

/* @var UploadUrlData $data */
$data = $api->images()
    ->private(false) // optional
    ->withMetadata(['user_id' => '123']) // optional
    ->getUploadUrl();
$data->uploadUrl; // https://upload.imagedelivery.net/2222222222222222222222/00000000-0000-0000-0000-000000000000"

```

You can find more information about direct creator uploads in the [Cloudflare Docs](https://developers.cloudflare.com/images/cloudflare-images/upload-images/direct-creator-upload/).

## Response Data
All responses are returned as data objects. Detailed information on the available data can be found by inspecting the following class properties:

* [ImageData](https://github.com/benbjurstrom/cloudflare-images-php/blob/main/src/Data/ImageData.php)
* [ImagesData](https://github.com/benbjurstrom/cloudflare-images-php/blob/main/src/Data/ImagesData.php)
* [UploadUrlData](https://github.com/benbjurstrom/cloudflare-images-php/blob/main/src/Data/UploadUrlData.php)
* [VariantData](https://github.com/benbjurstrom/cloudflare-images-php/blob/main/src/Data/VariantData.php)

## Private Images
Cloudflare allows you to configure an image to only be accessible with a signed URL token. To make an image private, chain `private(true)` onto your api intance before calling the `getUploadUrl`, `uploadFromUrl`, or `update` methods. For example:

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

## Other Image Methods
### Get A Paginated List of Images

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
### Upload From Url
```php
use BenBjurstrom\CloudflareImages\Data\ImageData
...

$url = 'https://en.wikipedia.org/wiki/File:Example.jpg'

/* @var ImageData $data */
$data = $api->images()
    ->private(false) // optional
    ->withMetadata(['user_id' => '123']) // optional
    ->uploadFromUrl($id);
```
### Update Image

⚠️ WARNING - Modifying an image's privacy setting will change the image's identifier.

```php
use BenBjurstrom\CloudflareImages\Data\ImageData
...

$id = '00000000-0000-0000-0000-000000000000'

/* @var ImageData $data */
$data = $api->images()
    ->private(false) // optional
    ->withMetadata(['user_id' => '123']) // optional
    ->update($id);

$data->id; // Contains a new id if the privacy setting was changed. If you are tracking IDs be sure to update your database.
```
### Delete Image
```php
$id = '00000000-0000-0000-0000-000000000000'
$data = $api->images()->delete($id);
$data // true
```

## Variant Methods
### Get All Variants
```php
use BenBjurstrom\CloudflareImages\Data\VariantsData
...

/* @var VariantsData $data */
$data = $api->variants()->all()
$data->variants[0]->id; // public
$data->variants[0]->width; // 1366
$data->variants[0]->height; // 768
```
