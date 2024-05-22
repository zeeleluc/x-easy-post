<?php
namespace App\Service;

use App\Models\Post;
use App\Query\PostQuery;

class GatherShillingProgress
{

    private PostQuery $postQuery;

    public function __construct()
    {
        $this->postQuery = new PostQuery();
    }

    /**
     * @throws \Exception
     */
    public function perProject(bool $scheduled = false): array
    {
        if ($scheduled) {
            $posts = $this->postQuery->getLastPostsScheduled();
        } else {
            $posts = $this->postQuery->getLastPosts(now()->subWeek());
        }
        if (!$posts) {
            return [];
        }

        $results = $this->calculateShilledPercentages($this->sumPerProject($posts));
        ksort($results);

        return $results;
    }
    
    private function getProjects(): array
    {
        return [
            'RipplePunks' => [
                flatten_string('RipplePunks'),
                flatten_string('RipplePunks QR'),
                flatten_string('Text Centered RipplePunks'),
            ],
            'PipingPunks' => [
                flatten_string('PipingPunks NFT'),
                flatten_string('PipingPunks Moving'),
            ],
            'LoadingPunks' => [
                flatten_string('LoadingPunks NFT'),
                flatten_string('LoadingPunks Pixel Count'),
            ],
            'BaseAliens' => [
                flatten_string('BaseAliens'),
                flatten_string('BaseAliens Moving'),
                flatten_string('Text Centered BaseAliens'),
            ],
            'SOLpepens' => [
                flatten_string('SOLpepens'),
            ],
            'LooneyLuca' => [
                flatten_string('Looney Luca'),
                flatten_string('Text Centered LooneyLuca'),
            ],
        ];
    }

    /**
     * @param array|Post[] $posts
     * @return array
     */
    private function sumPerProject(array $posts): array
    {
        $sumPerProject = [];

        foreach ($posts as $post) {
            if ($post->imageType) {
                if ($project = $this->getProjectForImageIdentifier($post->imageType)) {
                    if (!array_key_exists($project, $sumPerProject)) {
                        $sumPerProject[$project] = 1;
                    } else {
                        $sumPerProject[$project] += 1;
                    }
                }
            }
        }

        return $sumPerProject;
    }

    function calculateShilledPercentages(array $numberShilledPerProject): array
    {
        $totalShilledSum = array_sum($numberShilledPerProject);

        $percentages = [];
        foreach ($numberShilledPerProject as $project => $totalShilled) {
            $percentage = ($totalShilled / $totalShilledSum) * 100;
            $percentages[$project] = $percentage;
        }

        return $percentages;
    }

    private function getProjectForImageIdentifier(string $identifier):? string
    {
        foreach ($this->getProjects() as $project => $projectIdentifiers) {
            if (in_array($identifier, $projectIdentifiers, true)) {
                return $project;
            }
        }

        return null;
    }
}
