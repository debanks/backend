<?php namespace App\Jobs;

use App\Models\Subreddit;
use App\Services\RedditService;

class GatherRedditData {

    public function run() {
        $subreddits = Subreddit::all();
        $service    = new RedditService();

        foreach ($subreddits as $subreddit) {
            $threads = $service->getThreads($subreddit->name, $subreddit->limit);
            var_dump($threads);
        }
    }
}