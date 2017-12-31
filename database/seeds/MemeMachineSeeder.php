<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\MemeMachine\Competition;
use App\Models\MemeMachine\Event;
use App\Models\MemeMachine\Stage;
use App\Models\MemeMachine\Result;
use App\Models\MemeMachine\Poll;
use App\Models\MemeMachine\Choice;
use App\Models\MemeMachine\Answer;

class MemeMachineSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $users = User::all();
        $ana   = User::where('name', '=', 'Ana')->first();
        $david = User::where('name', '=', 'David')->first();

        $users = json_decode(json_encode($users), true);

        $this->competition2016($users);
        $this->competition2017($users, $ana, $david);
        $this->competition2018($users);
        $this->poll($users);
    }

    public function competition2016($users) {

        $rand = rand(0, count($users) - 1);

        $competition2016 = new Competition([
            'name'           => 'Fake Competition',
            'description'    => 'A fake competition to see what a more complete competition looks like.',
            'start_date'     => '2016-01-01',
            'end_date'       => '2016-12-31',
            'winner_user_id' => $users[$rand]['id']
        ]);
        $competition2016->save();

        $event1 = new Event([
            'name'           => 'Event 1',
            'description'    => 'First event of the fake competition where everyone is participating.',
            'start_date'     => '2016-03-01',
            'end_date'       => '2016-03-02',
            'competition_id' => $competition2016->id,
            'type'           => 'major',
            'status'         => 'completed'
        ]);
        $event1->save();

        $stage1 = new Stage([
            'name'        => 'Live Stage',
            'description' => 'The event happened live and only had one stage',
            'start_date'  => '2016-03-01',
            'end_date'    => '2016-03-02',
            'type'        => 'live',
            'status'      => 'complete',
            'event_id'    => $event1->id
        ]);
        $stage1->save();

        shuffle($users);

        $score = 10;
        foreach ($users as $user) {
            Result::create([
                'competition_id' => $competition2016->id,
                'event_id'       => $event1->id,
                'score'          => $score,
                'user_id'        => $user['id']
            ]);
            $score += 10;
        }

        $event2 = new Event([
            'name'           => 'Fake Poll Event',
            'description'    => 'Testing a 2 stage poll',
            'start_date'     => '2016-07-01',
            'end_date'       => '2016-08-01',
            'competition_id' => $competition2016->id,
            'type'           => 'minor',
            'status'         => 'completed'
        ]);
        $event2->save();

        $stage2 = new Stage([
            'name'        => 'Image Submission',
            'description' => 'Participants will submit issues to be judged later',
            'start_date'  => '2016-07-01',
            'end_date'    => '2016-07-22',
            'type'        => 'image-submit',
            'status'      => 'complete',
            'event_id'    => $event2->id
        ]);
        $stage2->save();

        $poll = new Poll([
            'name'        => 'What Minor event would you want?',
            'description' => 'Pick what kind of event you would want for our second minor event during the year.',
            'start_date'  => '2017-12-29',
            'end_date'    => '2018-03-01',
            'status'      => 'open'
        ]);
        $poll->save();

        $images = [
            "http://cdn.cgmagonline.com/wp-content/uploads/2017/03/nier-automata-revew-the-new-gold-standard-1068x601.jpg",
            "https://www.dualshockers.com/wp-content/uploads/2016/04/xenoblade-chronicles-3d-123122.jpg",
            "https://media2.nintendowire.com/wp-content/uploads/2017/01/XenobladeChronicles2-Switch-Field.jpg",
            "http://www.zelda.com/breath-of-the-wild/assets/media/header/Main-Day.jpg",
            "https://nintendo.corednacdn.com/prodcatalogue/product/4196/screenshot/13660.jpg",
            "https://vignette2.wikia.nocookie.net/codegeass/images/8/82/Code.Geass_.Boukoku.no.Akito.full.1898992.jpg/revision/latest/scale-to-width-down/2000?cb=20150717023546",
            "https://i.ytimg.com/vi/aNQvLECht08/maxresdefault.jpg",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTsg1qkiiGmz-HKjAyUAIGFWsPFBQ3EpKTj_q4jg6-wNUFlBhwI"
        ];

        shuffle($users);
        $choices = [];
        $results = [];

        foreach ($users as $i => $user) {
            $choice = new Choice([
                'poll_id'     => $poll->id,
                'choice'      => $user['name'] . "'s Image",
                'image_url'   => $images[$i],
                'description' => 'The image ' . $user['name'] . ' made',
                'user_id'     => $user['id']
            ]);
            $choice->save();
            $choices[]            = $choice;
            $results[$user['id']] = 0;
        }

        shuffle($users);

        foreach ($users as $user) {
            $vote  = rand(0, count($choices) - 1);
            $voted = $choices[$vote];
            Answer::create([
                'poll_id'   => $poll->id,
                'choice_id' => $voted->id,
                'user_id'   => $user['id']
            ]);
            $results[$voted->user_id] += 1;
        }

        $stage2 = new Stage([
            'name'        => 'Image Submission',
            'description' => 'Participants will submit issues to be judged later',
            'start_date'  => '2016-07-01',
            'end_date'    => '2016-07-22',
            'type'        => 'image-submit',
            'status'      => 'complete',
            'event_id'    => $event2->id
        ]);
        $stage2->save();

        $stage3 = new Stage([
            'name'        => 'Image Vote',
            'description' => 'Participants will vote on images submitted in the prior stage',
            'start_date'  => '2016-07-23',
            'end_date'    => '2016-08-01',
            'type'        => 'poll',
            'poll_id'     => $poll->id,
            'status'      => 'complete',
            'event_id'    => $event2->id
        ]);
        $stage3->save();

        arsort($results);
        $lastScore = false;
        $score     = 5 * count($choices);
        $nextScore = $score;

        foreach ($results as $userId => $amount) {
            if ($lastScore !== false && $lastScore !== $amount) {
                $score = $nextScore;
            }
            $nextScore = $nextScore - 5;
            $lastScore = $amount;
            Result::create([
                'competition_id' => $competition2016->id,
                'event_id'       => $event2->id,
                'score'          => $score,
                'user_id'        => $userId
            ]);
        }

        $event3 = new Event([
            'name'           => 'Event 3',
            'description'    => 'Final event of the fake competition where everyone is participating.',
            'start_date'     => '2016-11-22',
            'end_date'       => '2016-11-25',
            'competition_id' => $competition2016->id,
            'type'           => 'major',
            'status'         => 'completed'
        ]);
        $event3->save();

        $stage4 = new Stage([
            'name'        => 'Live Stage',
            'description' => 'The event happened live and only had one stage',
            'start_date'  => '2016-11-22',
            'end_date'    => '2016-11-25',
            'type'        => 'live',
            'status'      => 'complete',
            'event_id'    => $event3->id
        ]);
        $stage4->save();

        shuffle($users);

        $score = 10;
        foreach ($users as $user) {
            Result::create([
                'competition_id' => $competition2016->id,
                'event_id'       => $event3->id,
                'score'          => $score,
                'user_id'        => $user['id']
            ]);
            $score += 10;
        }
    }

    public function competition2017($users, $ana, $david) {

        $competition2017 = new Competition([
            'name'           => '1st Inaugural Competition 2017',
            'description'    => 'The first official Meme Machine Competition',
            'start_date'     => '2017-12-16',
            'end_date'       => '2017-12-23',
            'winner_user_id' => $ana->id
        ]);
        $competition2017->save();

        $event1 = new Event([
            'name'           => 'Dessert Cooking Competition',
            'description'    => 'Contestants will bake their submissions before the holiday party and have people at the party judge the desserts to determine a winner.',
            'start_date'     => '2017-12-16',
            'end_date'       => '2017-12-16',
            'competition_id' => $competition2017->id,
            'type'           => 'major',
            'status'         => 'completed'
        ]);
        $event1->save();

        $stage1 = new Stage([
            'name'        => 'Live Judging',
            'description' => 'People will cast ballots at the Marshall Holiday Party in a blind taste challenge to determine who has the best dessert.',
            'start_date'  => '2017-12-16',
            'end_date'    => '2017-12-16',
            'type'        => 'live',
            'status'      => 'complete',
            'event_id'    => $event1->id
        ]);
        $stage1->save();

        Result::create([
            'competition_id' => $competition2017->id,
            'event_id'       => $event1->id,
            'score'          => 20,
            'user_id'        => $ana->id
        ]);

        Result::create([
            'competition_id' => $competition2017->id,
            'event_id'       => $event1->id,
            'score'          => 10,
            'user_id'        => $david->id
        ]);

        $event2 = new Event([
            'name'           => 'Dessert Cooking Competition',
            'description'    => 'Contestants will bake their submissions before the holiday party and have people at the party judge the desserts to determine a winner.',
            'start_date'     => '2017-12-16',
            'end_date'       => '2017-12-16',
            'competition_id' => $competition2017->id,
            'type'           => 'major',
            'status'         => 'completed'
        ]);
        $event2->save();

        $stage2 = new Stage([
            'name'        => 'Live Race',
            'description' => 'Participants will race together around the track with the first person to finish being the winner.',
            'start_date'  => '2017-12-23',
            'end_date'    => '2017-12-23',
            'type'        => 'live',
            'status'      => 'complete',
            'event_id'    => $event2->id
        ]);
        $stage2->save();

        Result::create([
            'competition_id' => $competition2017->id,
            'event_id'       => $event2->id,
            'score'          => 20,
            'user_id'        => $ana->id
        ]);

        Result::create([
            'competition_id' => $competition2017->id,
            'event_id'       => $event2->id,
            'score'          => 10,
            'user_id'        => $david->id
        ]);
    }

    public function competition2018($users) {

        $competition2018 = new Competition([
            'name'           => '2nd Inaugural Competition 2017',
            'description'    => 'The second Meme Machine Competition featuring most of the group.',
            'start_date'     => '2018-01-01',
            'end_date'       => '2018-12-31',
            'winner_user_id' => null
        ]);
        $competition2018->save();
    }

    public function poll($users) {

        $poll = new Poll([
            'name'        => 'What Minor event would you want?',
            'description' => 'Pick what kind of event you would want for our second minor event during the year.',
            'start_date'  => '2017-12-29',
            'end_date'    => '2018-03-01',
            'status'      => 'open'
        ]);
        $poll->save();

        $choice1 = new Choice([
            'poll_id'     => $poll->id,
            'choice'      => 'Create a Meme',
            'description' => 'Each contestant must create and submit an original meme that will be blind voted by the other contestants.'
        ]);
        $choice1->save();

        $choice2 = new Choice([
            'poll_id'     => $poll->id,
            'choice'      => 'Group Portrait',
            'description' => 'Create a portrait of our group to be blind voted on by the other contestants.'
        ]);
        $choice2->save();

        $choice3 = new Choice([
            'poll_id'     => $poll->id,
            'choice'      => 'Double Elimination Coin Flip Challenge',
            'description' => 'Everyone participate in a double elimination bracket online coin flip challenge.'
        ]);
        $choice3->save();

        foreach ($users as $user) {
            $rand = rand(1, 10);
            if ($rand > 8) {
                continue;
            }
            if ($rand < 3) {
                Answer::create([
                    'poll_id'   => $poll->id,
                    'choice_id' => $choice1->id,
                    'user_id'   => $user['id']
                ]);
            } elseif ($rand < 6) {
                Answer::create([
                    'poll_id'   => $poll->id,
                    'choice_id' => $choice2->id,
                    'user_id'   => $user['id']
                ]);
            } else {
                Answer::create([
                    'poll_id'   => $poll->id,
                    'choice_id' => $choice3->id,
                    'user_id'   => $user['id']
                ]);
            }
        }
    }
}