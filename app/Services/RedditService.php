<?php namespace App\Services;

use GuzzleHttp\Client;

class RedditService {

    public $threadUrl = "https://www.reddit.com/r/%s/%s.json";
    public $commentUrl = "https://www.reddit.com/r/%s/comments/%s.json";
    public $accessUrl = 'https://www.reddit.com/api/v1/access_token';

    public $token = false;

    public function getAccessToken() {
        if ($this->token) {
            return $this->token;
        }

        $client = new Client();

        try {
            $response = $client->post($this->accessUrl, [
                'headers' => [
                    'Authorization' => env('REDDIT_AUTH'),
                    'User-Agent'    => 'my app'
                ],
                'body'    => [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => env('REDDIT_REFRESH')
                ]
            ]);
            return $response->json();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    public function getThreads($subreddit, $type, $limit) {

        $client = new Client();

        try {

            $response = $client->get(sprintf($this->threadUrl, $subreddit, $type), [
                'query'   => ['limit' => $limit],
                'headers' => [
                    'User-Agent' => 'my app'
                ]
            ]);
            return $response->json();
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }

    public function getComments($subreddit, $thread, $type, $limit) {
        $client = new Client();

        try {
            $response = $client->get(sprintf($this->commentUrl, $subreddit, $thread), [
                'query'   => [
                    'limit' => $limit,
                    'sort'  => $type,
                    'depth' => 3
                ],
                'headers' => [
                    'User-Agent' => 'my app'
                ]
            ]);;
            return $response->json();
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}