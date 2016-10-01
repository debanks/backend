<?php namspace App\Services;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\MultiTransferException;

public RedditService {

    public function getSubReddits() {
        $client = new Client();
        $request = $client->post($base, array(), $data);
        $request->addHeader('Authorization', $array['auth']);
        $requests[] = $request;

        try {
            $responses = $client->send($requests);
        } catch (MultiTransferException $exception){
            $this->logger->error($exception->getMessage());
        }
    }
}