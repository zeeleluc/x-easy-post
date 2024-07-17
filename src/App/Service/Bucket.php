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
        $key = str_replace('.png', '/Hypeomatic.png', $key);

        if (is_hypeomatic_website()) {
            $folder = env('S3_BUCKET_FOLDER_HYPEOMATIC');
        } else {
            $folder = env('S3_BUCKET_FOLDER_REGULAR');
        }

        try {
            $result = $this->s3->putObject([
                'Bucket' => env('S3_BUCKET_NAME'),
                'Key'    => $folder . '/' . $key,
                'Body'   => fopen($path, 'rb'),
                'ACL'    => 'public-read'
            ]);

            return $result['ObjectURL'];
        } catch (S3Exception $e) {
            throw new \Exception('Error uploading file: ' . $e->getMessage());
        }
    }
}
