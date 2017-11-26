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
            'email'             => 'test@test.com',
            'profile_photo_url' => 'https://s3.us-east-2.amazonaws.com/davis-images/delta.png',
            'description'       => 'Developer, Software Engineer, and Gamer',
            'password'          => bcrypt('test'),
        ]);
        $user->save();

        $gameSeeder    = new GameSeeder();
        $projectSeeder = new ProjectSeeder();
        $articleSeeder = new ArticleSeeder();

        $gameSeeder->run();
        $projectSeeder->run();
        $articleSeeder->run();
    }
}
