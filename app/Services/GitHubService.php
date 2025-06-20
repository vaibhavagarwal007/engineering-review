<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    public function getPullRequests($repo)
    {
        echo $url = "https://api.github.com/repos/".config('github_service.github.org')."/{$repo}/pulls";

        $response = Http::withToken(config('github_service.github.token'))
                        ->get($url, ['state' => 'open']);

        return $response->json();
    }
}
