<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $user = new User([
            'name'              => 'Davis',
            'email'             => 'davis@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $user = new User([
            'name'              => 'Ana',
            'email'             => 'ana@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $user = new User([
            'name'              => 'David',
            'email'             => 'david@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $user = new User([
            'name'              => 'Fritz',
            'email'             => 'fritz@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $user = new User([
            'name'              => 'Summit',
            'email'             => 'summit@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $user = new User([
            'name'              => 'Lee',
            'email'             => 'lee@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $user = new User([
            'name'              => 'Kylie',
            'email'             => 'kylie@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $user = new User([
            'name'              => 'Tyler',
            'email'             => 'tyler@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $gameSeeder    = new GameSeeder();
        $projectSeeder = new ProjectSeeder();
        $articleSeeder = new ArticleSeeder();
        $memeSeeder = new MemeMachineSeeder();

        $gameSeeder->run();
        $projectSeeder->run();
        $articleSeeder->run();
        $memeSeeder->run();
    }
}
