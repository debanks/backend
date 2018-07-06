<?php namespace App\Http\Controllers;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Fortnite\FortniteUser;
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
            'matches' => Match::orderBy('created_at', 'desc')->limit(50)->get(),
            'stats'   => \DB::select(\DB::raw("
                SELECT
                    type,
                    count(*) as matches,
                    sum(kills) as kills,
                    sum(died) as deaths,
                    avg(place) as place,
                    SUM(IF(place = 1, 1, 0)) as wins
                FROM matches
                WHERE name = 'Xenerius'
                GROUP BY 1
            "))
        ];
    }

    public function submit(Request $request) {

        $data  = $request->all();
        $match = new Match($data);
        $match->save();

        return [
            'status'  => true,
            'matches' => Match::orderBy('created_at', 'desc')->limit(50)->get(),
            'stats'   => \DB::select(\DB::raw("
                SELECT
                    type,
                    count(*) as matches,
                    sum(kills) as kills,
                    sum(died) as deaths,
                    avg(place) as place,
                    SUM(IF(place = 1, 1, 0)) as wins
                FROM matches
                WHERE name = 'Xenerius'
                GROUP BY 1
            "))
        ];
    }

    public function stats(Request $request) {

        return FortniteUser::select(\DB::raw("
            *,
            IF(solo_matches > 0, solo_kills / solo_matches, 0) as solo_kd,
            IF(solo_matches > 0, solo_wins / solo_matches, 0) as solo_winrate,
            IF(duo_matches > 0, duo_kills / duo_matches, 0) as duo_kd,
            IF(duo_matches > 0, duo_wins / duo_matches, 0) as duo_winrate,
            IF(squad_matches > 0, squad_kills / squad_matches, 0) as squad_kd,
            IF(squad_matches > 0, squad_wins / squad_matches, 0) as squad_winrate
        "))->get();
    }

    public function player(Request $request, $name) {

        $player = FortniteUser::where('name', '=', $name)->first();

        if (!$player) {
            return ['status' => false];
        }

        $last24 = \DB::select(\DB::raw("
            SELECT
                SUM(solo_matches) as solo_matches,
                SUM(solo_kills) as solo_kills,
                SUM(solo_wins) as solo_wins,
                SUM(solo_mmr) as solo_mmr,
                IF(SUM(solo_matches) > 0, SUM(solo_kills) / SUM(solo_matches), 0) as solo_kd,
                IF(SUM(solo_matches) > 0, SUM(solo_wins) / SUM(solo_matches), 0) as solo_winrate,
                SUM(duo_matches) as duo_matches,
                SUM(duo_kills) as duo_kills,
                SUM(duo_wins) as duo_wins,
                SUM(duo_mmr) as duo_mmr,
                IF(SUM(duo_matches) > 0, SUM(duo_kills) / SUM(duo_matches), 0) as duo_kd,
                IF(SUM(duo_matches) > 0, SUM(duo_wins) / SUM(duo_matches), 0) as duo_winrate,
                SUM(squad_matches) as squad_matches,
                SUM(squad_kills) as squad_kills,
                SUM(squad_wins) as squad_wins,
                SUM(squad_mmr) as squad_mmr,
                IF(SUM(squad_matches) > 0, SUM(squad_kills) / SUM(squad_matches), 0) as squad_kd,
                IF(SUM(squad_matches) > 0, SUM(squad_wins) / SUM(squad_matches), 0) as squad_winrate
            FROM fortnite_stat_diffs
            WHERE user_id = $player->id and created_at >= NOW() - INTERVAL 24 HOUR
        "));

        $lastWeek = \DB::select(\DB::raw("
            SELECT
                SUM(solo_matches) as solo_matches,
                SUM(solo_kills) as solo_kills,
                SUM(solo_wins) as solo_wins,
                SUM(solo_mmr) as solo_mmr,
                IF(SUM(solo_matches) > 0, SUM(solo_kills) / SUM(solo_matches), 0) as solo_kd,
                IF(SUM(solo_matches) > 0, SUM(solo_wins) / SUM(solo_matches), 0) as solo_winrate,
                SUM(duo_matches) as duo_matches,
                SUM(duo_kills) as duo_kills,
                SUM(duo_wins) as duo_wins,
                SUM(duo_mmr) as duo_mmr,
                IF(SUM(duo_matches) > 0, SUM(duo_kills) / SUM(duo_matches), 0) as duo_kd,
                IF(SUM(duo_matches) > 0, SUM(duo_wins) / SUM(duo_matches), 0) as duo_winrate,
                SUM(squad_matches) as squad_matches,
                SUM(squad_kills) as squad_kills,
                SUM(squad_wins) as squad_wins,
                SUM(squad_mmr) as squad_mmr,
                IF(SUM(squad_matches) > 0, SUM(squad_kills) / SUM(squad_matches), 0) as squad_kd,
                IF(SUM(squad_matches) > 0, SUM(squad_wins) / SUM(squad_matches), 0) as squad_winrate
            FROM fortnite_stat_diffs
            WHERE user_id = $player->id and created_at >= NOW() - INTERVAL 7 DAY
        "));

        $charting = \DB::select(\DB::raw("
            SELECT
                *,
                IF(solo_matches > 0, solo_kills / solo_matches, 0) as solo_kd,
                IF(solo_matches > 0, solo_wins / solo_matches, 0) as solo_winrate,
                IF(duo_matches > 0, duo_kills / duo_matches, 0) as duo_kd,
                IF(duo_matches > 0, duo_wins / duo_matches, 0) as duo_winrate,
                IF(squad_matches > 0, squad_kills / squad_matches, 0) as squad_kd,
                IF(squad_matches > 0, squad_wins / squad_matches, 0) as squad_winrate
            FROM fortnite_stats
            WHERE user_id = $player->id and created_at >= NOW() - INTERVAL 30 DAY
        "));

        return [
            'overall'  => $player,
            'last24'   => $last24,
            'lastWeek' => $lastWeek,
            'charting' => $charting
        ];
    }
}