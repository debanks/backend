<?php namespace App\Jobs;

use App\Models\Comment;
use App\Models\Stat;
use App\Models\Subreddit;
use App\Models\Thread;
use App\Services\RedditService;

class GatherRedditData {

    public function run() {
        $subreddits = Subreddit::orderBy('updated_at','asc')->limit(3)->get();
        $service = new RedditService();

        foreach ($subreddits as $subreddit) {

            $subreddit->syncs += 1;
            $subreddit->save();

            $threads = $service->getThreads($subreddit->name, 'hot', $subreddit->limit);
            $threads = array_merge($threads, $service->getThreads($subreddit->name, 'new', $subreddit->limit));
            if (!isset($threads['data']) || !isset($threads['data']['children'])) {
                return;
            }

            foreach ($threads['data']['children'] as $thr) {

                $data = $thr['data'];

                $thread = Thread::where('thread_id', '=', $data['id'])->first();
                $stat = new Stat([
                    'subreddit' => $subreddit->name,
                    'thread_id' => $data['id']
                ]);

                if ($thread) {
                    $stat->ups = $data['ups'] - $thread->ups;
                    $stat->downs = $data['downs'] - $thread->downs;
                    $stat->score = $data['score'] - $thread->score;
                    $stat->comments = $data['num_comments'] - $thread->comments;

                    $thread->comments = $data['num_comments'];
                    $thread->ups = $data['ups'];
                    $thread->downs = $data['downs'];
                    $thread->score = $data['score'];
                    $thread->title = $data['title'];
                } else {
                    $stat->ups = $data['ups'];
                    $stat->downs = $data['downs'];
                    $stat->score = $data['score'];
                    $stat->comments = $data['num_comments'];

                    $thread = new Thread([
                        'subreddit' => $subreddit->name,
                        'over18'    => $data['over_18'],
                        'downs'     => $data['downs'],
                        'ups'       => $data['ups'],
                        'url'       => $data['url'],
                        'score'     => $data['score'],
                        'spoiler'   => $data['spoiler'],
                        'author'    => $data['author'],
                        'title'     => $data['title'],
                        'comments'  => $data['num_comments'],
                        'thread_id' => $data['id']
                    ]);

                }
                $thread->save();
                $stat->save();

                $this->getComments($service, $subreddit, $thread);
            }
        }
    }

    public function getComments(RedditService $service, Subreddit $subreddit, Thread $thread) {
        $comments = $service->getComments($subreddit->name, $thread->thread_id, 'best', 15);
        if (is_array($comments) && isset($comments[1]) && !isset($comments[1]['data']) || !isset($comments[1]['data']['children'])) {
            return;
        }

        foreach ($comments[1]['data']['children'] as $com) {

            $data = $com['data'];

            $this->handleComment($subreddit, $thread, $data);
        }
    }

    public function handleComment($subreddit, $thread, $data, Comment $parent = null) {

        if (!isset($data['body'])) {
            return;
        }
        $comment = Comment::where('thread_id', '=', $thread->thread_id)->where('id', '=', $data['id'])->first();
        $stat = new Stat([
            'subreddit'  => $subreddit->name,
            'thread_id'  => $thread->thread_id,
            'comment_id' => $data['id']
        ]);

        if ($comment) {
            $stat->ups = $data['ups'] - $comment->ups;
            $stat->downs = $data['downs'] - $comment->downs;
            $stat->score = $data['score'] - $comment->score;
            $stat->comments = 0;
            $comment->body = $data['body'];
            $comment->body_html = $data['body_html'];
            $comment->ups = $data['ups'];
            $comment->downs = $data['downs'];
            $comment->score = $data['score'];
            $comment->title = $data['title'];
        } else {
            $stat->ups = $data['ups'];
            $stat->downs = $data['downs'];
            $stat->score = $data['score'];
            $stat->comments = 0;

            $comment = new Comment([
                'subreddit'         => $subreddit->name,
                'thread_id'         => $thread->thread_id,
                'downs'             => $data['downs'],
                'ups'               => $data['ups'],
                'body'              => $data['body'],
                'score'             => $data['score'],
                'body_html'         => $data['body_html'],
                'comment_id'        => $data['id'],
                'author'            => $data['author'],
                'parent_comment_id' => $parent ? $parent->comment_id : null
            ]);
        }

        $stat->save();
        $comment->save();

        if (isset($data['replies']) && isset($data['replies']['data']) && isset($data['replies']['data']['children']) && is_array($data['replies']['data']['children'])) {
            foreach ($data['replies']['data']['children'] as $com) {
                $data2 = $com['data'];

                $this->handleComment($subreddit, $thread, $data2, $comment);
            }
        }
    }
}