<?php namespace App\Http\Controllers;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\MemeMachine\Competition;
use App\Models\MemeMachine\Poll;
use App\Models\MemeMachine\Event;
use App\Models\MemeMachine\Stage;
use App\Models\Memory;
use App\Models\Project;
use App\Models\Thought;
use Illuminate\Http\Request;
use App\Models\ContentQuery;
use Illuminate\Http\Response;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;

class MemeMachineController extends Controller {

    public function home() {

        return [
            'competitions' => Competition::orderBy('end_date', 'desc')->get(),
            'polls'        => Poll::orderBy('end_date', 'desc')
        ];
    }

    public function competition(Request $request, $id) {

        $competition = Competition::find($id);

        $events = Event::where('competition_id', '=', $id)->orderBy('start_date', 'asc')->get();

        foreach ($events as $event) {
            $event->stages = Stage::where('event_id', '=', $event->id)->orderBy('start_date', 'asc')->get();
        }

        return [
            'competition' => $competition,
            'events'      => $events
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

        $competition = Memory::find($id);

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