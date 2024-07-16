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
