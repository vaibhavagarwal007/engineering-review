<?php 

namespace App\Http\Controllers;

use App\Services\GitHubService;

class PullRequestController extends Controller
{
    protected $gitHubService;

    public function __construct(GitHubService $gitHubService)
    {
        $this->gitHubService = $gitHubService;
    }

    public function index()
    {
        $repo = config('github_service.github.repo');
        $pullRequests = $this->gitHubService->getPullRequests($repo);
        return view('pulls.index', compact('pullRequests', 'repo'));

    }

    public function commits()
    {
        $repo = request()->get('repo', config('github_service.github.repo'));
        $commits = $this->gitHubService->getCommits($repo);

        $commitData = collect($commits)->map(function ($commit) {
            return [
                'author' => $commit['author']['login'] ?? 'Unknown',
                'profile_url' => $commit['author']['html_url'] ?? null,
                'message' => $commit['commit']['message'],
                'timestamp' => $commit['commit']['committer']['date'],
            ];
        });

        return view('commits.index', ['commits' => $commitData]);
    }
}
