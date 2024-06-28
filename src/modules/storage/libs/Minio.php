<?php

namespace Modules\Storage\Libs;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Modules\Storage\StorageInterface;

final class Minio implements StorageInterface
{
    private $filesystem;

    function __construct()
    {
        $client = new S3Client([
            'endpoint' => $_ENV['AWS_HOST_NAME'],
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => $_ENV['AWS_KEY'],
                'secret' => $_ENV['AWS_SECRET']
            ],
            'region' => $_ENV['AWS_REGION'],
            'version' => $_ENV['AWS_VERSION'],
        ]);

        $options = [
            'override_visibility_on_copy' => true
        ];

        $adapter = new AwsS3Adapter($client, $_ENV['AWS_BUCKET_NAME'], '', $options);
        $this->filesystem = new Filesystem($adapter);
    }

    public function write(string $path, $content): bool
    {
        $stream = fopen($content['tmp_name'], 'r+');
        $path = $path . '/' . $content['name'];

        $response = false;

        if ($this->filesystem->writeStream($path, $stream)) {
            $response = true;
        }

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $response;
    }

    public function read(string $path): string
    {
        return '';
    }

    public function delete(string $path): void
    {
    }

    public function buckets(): array
    {
        return $this->filesystem->listContents('', true);
    }
}
