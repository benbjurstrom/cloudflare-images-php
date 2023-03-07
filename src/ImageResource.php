<?php

namespace BenBjurstrom\CloudflareImages;

use BenBjurstrom\CloudflareImages\Data\ImageData;
use BenBjurstrom\CloudflareImages\Data\ImagesData;
use BenBjurstrom\CloudflareImages\Data\UploadUrlData;
use BenBjurstrom\CloudflareImages\Requests\DeleteImage;
use BenBjurstrom\CloudflareImages\Requests\GetImage;
use BenBjurstrom\CloudflareImages\Requests\GetImages;
use BenBjurstrom\CloudflareImages\Requests\PatchImage;
use BenBjurstrom\CloudflareImages\Requests\PostImage;
use BenBjurstrom\CloudflareImages\Requests\PostUploadUrl;
use Exception;

class ImageResource extends Resource
{
    /**
     * @var array<string, string>
     */
    protected ?array $metadata = null;

    protected ?bool $private = null;

    protected ?string $customId = null;

    public function private(?bool $private = true): self
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @param  array<string, string>  $metadata
     */
    public function withMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function withCustomId(string $id): self
    {
        $this->customId = $id;

        return $this;
    }

    public function list(?int $perPage = null, ?int $page = null): ImagesData
    {
        $request = new GetImages();

        if ($page) {
            $request->query()->add('page', $page);
        }
        if ($perPage) {
            $request->query()->add('per_page', $perPage);
        }

        $response = $this->connector->send($request);
        $data = $response->dtoOrFail();
        if (! $data instanceof ImagesData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    public function get(string $id): ImageData
    {
        $request = new GetImage($id);
        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof ImageData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    public function delete(string $id): bool
    {
        $request = new DeleteImage($id);
        $response = $this->connector->send($request);

        return $response->successful();
    }

    public function update(string $id): ImageData
    {
        $request = new PatchImage($id);
        if ($this->metadata) {
            $request->body()->add('metadata', $this->metadata);
        }

        if (! is_null($this->private)) {
            $request->body()->add('requireSignedURLs', $this->private);
        }

        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof ImageData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    public function upload(string $image, string $fileName): ImageData
    {
        $request = new PostImage();
        $request->body()->add(
            name: 'file',
            contents: $image,
            filename: $fileName
        );
        $request = $this->addMetadata($request);
        $request = $this->addPrivacy($request);
        $request = $this->addCustomId($request);
        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof ImageData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    public function uploadFromUrl(string $url): ImageData
    {
        $request = new PostImage();
        $request->body()->add(
            name: 'url',
            contents: $url,
        );

        $request = $this->addMetadata($request);
        $request = $this->addPrivacy($request);
        $request = $this->addCustomId($request);
        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof ImageData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    public function getUploadUrl(): UploadUrlData
    {
        $request = new PostUploadUrl();
        $request = $this->addMetadata($request);
        $request = $this->addPrivacy($request);
        $request = $this->addCustomId($request);

        $response = $this->connector->send($request);

        $data = $response->dtoOrFail();
        if (! $data instanceof UploadUrlData) {
            throw new Exception('Unexpected data type');
        }

        return $data;
    }

    protected function addPrivacy(PostUploadUrl|PostImage $request): PostUploadUrl|PostImage
    {
        if (! is_null($this->private)) {
            $request->body()->add(
                name: 'requireSignedURLs',
                contents: $this->private ? 'true' : 'false',
            );
        }

        return $request;
    }

    protected function addMetadata(PostUploadUrl|PostImage $request): PostUploadUrl|PostImage
    {
        if ($this->metadata) {
            $request->body()->add(
                name: 'metadata',
                contents: json_encode($this->metadata, JSON_THROW_ON_ERROR)
            );
        }

        return $request;
    }

    protected function addCustomId(PostUploadUrl|PostImage $request): PostUploadUrl|PostImage
    {
        if ($this->customId) {
            $request->body()->add(
                name: 'id',
                contents: $this->customId
            );
        }

        return $request;
    }
}
