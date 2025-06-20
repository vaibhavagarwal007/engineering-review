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
}
