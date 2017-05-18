<?php namespace App\Services;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\MultiTransferException;

class RedditService {

    public $threadUrl = "https://www.reddit.com/r/%s.json";
    public $commentUrl = "https://www.reddit.com/r/%s/comments/%s/%s.json";

    public function getThreads($subreddit, $limit) {
        $client = new Client();
        $request = $client->get(sprintf($this->threadUrl, $subreddit), ['limit' => $limit]);
        $requests[] = $request;

        try {
            $responses = $client->send($requests);
            return $responses->json();
        } catch (MultiTransferException $exception){
            $this->logger->error($exception->getMessage());
        }
    }

    public function getComments($subreddit, $thread, $type, $limit) {
        $client = new Client();
        $request = $client->get(sprintf($this->commentUrl, $subreddit, $thread, $type), ['limit' => $limit, 'depth' => 3]);
        $requests[] = $request;

        try {
            $responses = $client->send($requests);
            return $responses->json();
        } catch (MultiTransferException $exception){
            $this->logger->error($exception->getMessage());
        }
    }
}