<?php namespace App\Http\Controllers;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Match;
use App\Models\MemeMachine\Competition;
use App\Models\MemeMachine\Poll;
use App\Models\MemeMachine\Event;
use App\Models\MemeMachine\Result;
use App\Models\MemeMachine\Stage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;

class FortniteController extends Controller {

    public function home() {

        return [
            'matches' => Match::orderBy('created_at', 'desc')->limit(50)
        ];
    }

    public function submit(Request $request) {

        $data = $request->all();
        $match = new Match($data);
        $match->save();

        return [
            'status' => true,
            'matches' => Match::orderBy('created_at', 'desc')->limit(50)
        ];
    }
}