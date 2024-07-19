<?php
namespace App\Service\Projects;

use App\Models\Post;
use App\Query\PostQuery;
use App\Service\Images\BaseImage;
use App\Service\Images\ImagesHelper;

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
        $projects = [];

        foreach (Projects::getAll() as $project) {
            foreach (ImagesHelper::getImagesClassesForProject($project) as $image) { /* @var $image BaseImage */
                $projects[$project][] = $image::getSlug();
            }
        }

        return $projects;
    }

    /**
     * @param array|Post[] $posts
     * @return array
     */
    private function sumPerProject(array $posts): array
    {
        $sumPerProject = [];

        foreach ($posts as $post) {
            if (!array_key_exists($post->project, $sumPerProject)) {
                $sumPerProject[$post->project] = 1;
            } else {
                $sumPerProject[$post->project] += 1;
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
}
