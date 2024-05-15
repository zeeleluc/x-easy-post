<?php
namespace App\Service;

use App\Slack;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Bucket
{
    private S3Client $s3;

    public function __construct()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'credentials' => [
                'key' => env('S3_BUCKET_ACCESS_KEY'),
                'secret' => env('S3_BUCKET_SECRET_KEY'),
            ],
            'region' => 'sfo3',
            'endpoint' => 'https://sfo3.digitaloceanspaces.com',
        ]);
    }

    public function uploadFile(string $path, string $key):? string
    {
        try {
            $result = $this->s3->putObject([
                'Bucket' => env('S3_BUCKET_NAME'),
                'Key'    => 'text-images/ ' . $key,
                'Body'   => fopen($path, 'rb'),
                'ACL'    => 'public-read'
            ]);

            return $result['ObjectURL'];
        } catch (S3Exception $e) {
            (new Slack())->sendErrorMessage('Error uploading file: ' . $e->getMessage());
        }

        return null;
    }
}
