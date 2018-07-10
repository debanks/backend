<?php namespace App\Http\Controllers;

use App\Console\Commands\FortniteCommand;
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
use Fortnite\Auth as FnAuth;

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

    public function addPlayer(Request $request, $name) {

        $player = FortniteUser::where('name', '=', $name)->first();

        if ($player) {
            return [
                'status' => -1,
                'player' => $player
            ];
        }

        $auth = FnAuth::login(env('FORTNITE_USER'), env('FORTNITE_PASS'));

        $stats    = $auth->profile->stats->lookup($name);
        $existing = new FortniteUser([
            'name'          => $name,
            'solo_matches'  => $stats->pc->solo->matches_played,
            'solo_wins'     => $stats->pc->solo->wins,
            'solo_kills'    => $stats->pc->solo->kills,
            'duo_matches'   => $stats->pc->duo->matches_played,
            'duo_wins'      => $stats->pc->duo->wins,
            'duo_kills'     => $stats->pc->duo->kills,
            'squad_matches' => $stats->pc->squad->matches_played,
            'squad_wins'    => $stats->pc->squad->wins,
            'squad_kills'   => $stats->pc->squad->kills,
            'collect'       => 1
        ]);

        $existing->solo_mmr  = FortniteCommand::calculateMMR($existing, 'solo');
        $existing->duo_mmr   = FortniteCommand::calculateMMR($existing, 'duo');
        $existing->squad_mmr = FortniteCommand::calculateMMR($existing, 'squad');
        $existing->save();

        return [
            'status' => 1,
            'player' => $existing
        ];
    }

    public function stats(Request $request) {

        return [
            'players' => \DB::select(\DB::raw("
                SELECT
                    fs.*,
                    IF(solo_matches > 0, solo_kills / solo_matches, 0) as solo_kd,
                    IF(solo_matches > 0, solo_wins / solo_matches, 0) as solo_winrate,
                    IF(duo_matches > 0, duo_kills / duo_matches, 0) as duo_kd,
                    IF(duo_matches > 0, duo_wins / duo_matches, 0) as duo_winrate,
                    IF(squad_matches > 0, squad_kills / squad_matches, 0) as squad_kd,
                    IF(squad_matches > 0, squad_wins / squad_matches, 0) as squad_winrate,
                    r1.rank as solo_rank,
                    r1.image as solo_rank_image,
                    r2.rank as duo_rank,
                    r2.image as duo_rank_image,
                    r3.rank as squad_rank,
                    r3.image as squad_rank_image
                FROM fortnite_users as fs
                    LEFT JOIN ranks r1 on fs.solo_mmr >= r1.mmr_start and fs.solo_mmr <= r1.mmr_end
                    LEFT JOIN ranks r2 on fs.duo_mmr >= r2.mmr_start and fs.duo_mmr <= r2.mmr_end
                    LEFT JOIN ranks r3 on fs.squad_mmr >= r3.mmr_start and fs.squad_mmr <= r3.mmr_end
                GROUP BY fs.id
            "))
        ];
    }

    public function player(Request $request, $name) {

        $players = \DB::select(\DB::raw("
            SELECT
                fs.*,
                r1.rank as solo_rank,
                r1.image as solo_rank_image,
                r2.rank as duo_rank,
                r2.image as duo_rank_image,
                r3.rank as squad_rank,
                r3.image as squad_rank_image
            FROM fortnite_users as fs
                LEFT JOIN ranks r1 on fs.solo_mmr >= r1.mmr_start and fs.solo_mmr <= r1.mmr_end
                LEFT JOIN ranks r2 on fs.duo_mmr >= r2.mmr_start and fs.duo_mmr <= r2.mmr_end
                LEFT JOIN ranks r3 on fs.squad_mmr >= r3.mmr_start and fs.squad_mmr <= r3.mmr_end
            WHERE fs.name = '$name'
            
        "));

        $players = json_decode(json_encode($players), true);

        if (count($players) == 0) {
            return ['status' => false];
        }

        $player = $players[0];

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
            WHERE user_id = " . $player['id'] . " and created_at >= NOW() - INTERVAL 24 HOUR
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
            WHERE user_id = " . $player['id'] . " and created_at >= NOW() - INTERVAL 7 DAY
        "));

        $charting = \DB::select(\DB::raw("
            SELECT
                CONCAT(SUBSTRING(created_at, 1, 10), ' 00:00:00') as created_at,
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
            WHERE user_id = " . $player['id'] . "
            GROUP BY 1
        "));

        return [
            'overall'  => $player,
            'last24'   => $last24,
            'lastWeek' => $lastWeek,
            'charting' => $charting
        ];
    }
}