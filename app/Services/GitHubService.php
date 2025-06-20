<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    public function getPullRequests($repo)
    {
        $url = "https://api.github.com/repos/".config('github_service.github.org')."/{$repo}/pulls";

        $response = Http::withToken(config('github_service.github.token'))
                        ->get($url, ['state' => 'all']);

        return $response->json();
    }

    public function getCommits($repo = null, $perPage = 100, $page = 1)
    {
        $repo = $repo ?? config('github_service.github.repo');
        $org = config('github_service.github.org');

        $url = "https://api.github.com/repos/{$org}/{$repo}/commits";

        $response = Http::withToken(config('github_service.github.token'))
            ->get($url, [
                'per_page' => $perPage,
                'page' => $page,
            ]);

        return $response->json();
    }
}
