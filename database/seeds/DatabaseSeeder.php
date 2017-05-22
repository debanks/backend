<?php

use Illuminate\Database\Seeder;
use App\Models\Subreddit;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Subreddit::create([
            'name'    => 'programming',
            'threads' => 20,
            'weight'  => 1
        ]);
    }
}
