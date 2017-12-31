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

        $competition2018 = new Competition([
            'name'           => '2nd Inaugural Competition 2017',
            'description'    => 'The second Meme Machine Competition featuring most of the group.',
            'start_date'     => '2018-01-01',
            'end_date'       => '2018-12-31',
            'winner_user_id' => null
        ]);
        $competition2018->save();

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
                    'user_id'   => $user->id
                ]);
            } elseif ($rand < 6) {
                Answer::create([
                    'poll_id'   => $poll->id,
                    'choice_id' => $choice2->id,
                    'user_id'   => $user->id
                ]);
            } else {
                Answer::create([
                    'poll_id'   => $poll->id,
                    'choice_id' => $choice3->id,
                    'user_id'   => $user->id
                ]);
            }
        }
    }
}