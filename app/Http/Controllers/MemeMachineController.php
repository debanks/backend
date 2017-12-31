<?php namespace App\Http\Controllers;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\MemeMachine\Competition;
use App\Models\MemeMachine\Poll;
use App\Models\MemeMachine\Event;
use App\Models\MemeMachine\Result;
use App\Models\MemeMachine\Stage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;

class MemeMachineController extends Controller {

    public function home() {

        return [
            'competitions' => \DB::select(\DB::raw("
                SELECT
                    c.*,
                    u.id as user_id,
                    u.name as name,
                    u.profile_image_url,
                    u.description,
                FROM competitions c
                    LEFT JOIN users u on u.id = c.winner_user_id
                GROUP BY c.id
                ORDER BY c.end_date desc
            ")),
            'polls'        => Poll::orderBy('end_date', 'desc')
        ];
    }

    public function competition(Request $request, $id) {

        $competition = \DB::select(\DB::raw("
                SELECT
                    c.*,
                    u.id as user_id,
                    u.name as name,
                    u.profile_image_url,
                    u.description,
                FROM competitions c
                    LEFT JOIN users u on u.id = c.winner_user_id
                WHERE c.id = $id
                GROUP BY c.id
                ORDER BY c.end_date desc
            "));

        $events  = Event::where('competition_id', '=', $id)->orderBy('start_date', 'asc')->get();
        $results = [];

        foreach ($events as $event) {

            $scores = \DB::select(\DB::raw("
                SELECT
                    u.id as user_id,
                    u.name as name,
                    u.profile_image_url,
                    u.description,
                    sum(r.score) as score
                FROM results r 
                    LEFT JOIN users u on u.id = r.user_id
                WHERE r.event_id = $event->id
                GROUP BY u.id
                ORDER By r.score desc
            "));

            foreach ($scores as $score) {
                if ($results[$score->user_id]) {
                    $results[$score->user_id] = [
                        'user_id'           => $score->user_id,
                        'name'              => $score->name,
                        'profile_photo_url' => $score->profile_photo_url,
                        'score'             => 0
                    ];
                }
                $results[$score->user_id] += $score->score;
            }

            $event->stages  = Stage::where('event_id', '=', $event->id)->orderBy('start_date', 'asc')->get();
            $event->results = $scores;
        }

        return [
            'competition' => $competition,
            'events'      => $events,
            'results'     => $results
        ];
    }

    public function insertCompetition(Request $request) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();

        unset($data['htmlContent']);

        if (!$user || $user->role !== 'admin') {
            return ['status' => false];
        }

        $competition = new Competition($data);
        $competition->save();

        return [
            'message' => 'Success',
            'url'     => '/competition/' . $competition->id
        ];
    }

    public function updateCompetition(Request $request, $id) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();

        $competition = Competition::find($id);

        unset($data['htmlContent']);

        if (!$user || $user->role !== 'admin') {
            return ['status' => false];
        }

        foreach ($data as $key => $value) {
            $competition->{$key} = $value;
        }

        $competition->save();

        return [
            'message' => 'Success'
        ];
    }
}