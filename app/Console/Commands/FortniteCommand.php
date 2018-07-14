<?php

namespace App\Console\Commands;

use App\Models\Fortnite\FortniteStat;
use App\Models\Fortnite\FortniteStatDiff;
use App\Models\Fortnite\FortniteUser;
use Illuminate\Console\Command;
use Fortnite\Auth;
use Fortnite\PlayablePlatform;
use Fortnite\Mode;
use Fortnite\Language;
use Fortnite\NewsType;
use Fortnite\Platform;

class FortniteCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fortnite:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $users = FortniteUser::where('collect', '=', 1)->get();
        $auth  = Auth::login(env('FORTNITE_USER'), env('FORTNITE_PASS'));

        foreach ($users as $existing) {
            $stats = $auth->profile->stats->lookup($existing->name);

            $new = new FortniteStat([
                'user_id'       => $existing->id,
                'solo_matches'  => $stats->pc->solo->matches_played,
                'solo_wins'     => $stats->pc->solo->wins,
                'solo_kills'    => $stats->pc->solo->kills,
                'duo_matches'   => $stats->pc->duo->matches_played,
                'duo_wins'      => $stats->pc->duo->wins,
                'duo_kills'     => $stats->pc->duo->kills,
                'squad_matches' => $stats->pc->squad->matches_played,
                'squad_wins'    => $stats->pc->squad->wins,
                'squad_kills'   => $stats->pc->squad->kills,
                'solo_score'    => $stats->pc->solo->score,
                'duo_score'     => $stats->pc->duo->score,
                'squad_score'   => $stats->pc->squad->score
            ]);

            $new->solo_mmr  = $this->calculateMMR($new, 'solo');
            $new->duo_mmr   = $this->calculateMMR($new, 'duo');
            $new->squad_mmr = $this->calculateMMR($new, 'squad');
            $new->save();

            $diff = new FortniteStatDiff([
                'user_id'       => $existing->id,
                'solo_matches'  => $stats->pc->solo->matches_played - $existing->solo_matches,
                'solo_wins'     => $stats->pc->solo->wins - $existing->solo_wins,
                'solo_kills'    => $stats->pc->solo->kills - $existing->solo_kills,
                'duo_matches'   => $stats->pc->duo->matches_played - $existing->duo_matches,
                'duo_wins'      => $stats->pc->duo->wins - $existing->duo_wins,
                'duo_kills'     => $stats->pc->duo->kills - $existing->duo_kills,
                'squad_matches' => $stats->pc->squad->matches_played - $existing->squad_matches,
                'squad_wins'    => $stats->pc->squad->wins - $existing->squad_wins,
                'squad_kills'   => $stats->pc->squad->kills - $existing->squad_kills,
                'solo_mmr'      => $new->solo_mmr - $existing->solo_mmr,
                'duo_mmr'       => $new->duo_mmr - $existing->duo_mmr,
                'squad_mmr'     => $new->squad_mmr - $existing->squad_mmr,
                'solo_score'    => $stats->pc->solo->score - $existing->solo_score,
                'duo_score'     => $stats->pc->duo->score - $existing->duo_score,
                'squad_score'   => $stats->pc->squad->score - $existing->squad_score
            ]);
            $diff->save();

            $existing->solo_matches  = $stats->pc->solo->matches_played;
            $existing->solo_wins     = $stats->pc->solo->wins;
            $existing->solo_kills    = $stats->pc->solo->kills;
            $existing->duo_matches   = $stats->pc->duo->matches_played;
            $existing->duo_wins      = $stats->pc->duo->wins;
            $existing->duo_kills     = $stats->pc->duo->kills;
            $existing->squad_matches = $stats->pc->squad->matches_played;
            $existing->squad_wins    = $stats->pc->squad->wins;
            $existing->squad_kills   = $stats->pc->squad->kills;
            $existing->solo_score    = $stats->pc->solo->score;
            $existing->duo_score     = $stats->pc->duo->score;
            $existing->squad_score   = $stats->pc->squad->score;
            $existing->solo_mmr      = $this->calculateMMR($existing, 'solo');
            $existing->duo_mmr       = $this->calculateMMR($existing, 'duo');
            $existing->squad_mmr     = $this->calculateMMR($existing, 'squad');
            $existing->save();
        }

    }

    public static function calculateMMR($stat, $type) {

        $killPoints  = 5000;
        $scorePoints = 5000;

        if ($type == 'solo') {
            $kd    = $stat->solo_matches > 0 ? $stat->solo_kills / $stat->solo_matches : 0;
            $score = $stat->solo_matches > 0 ? $stat->solo_score / $stat->solo_matches : 0;
        } else if ($type == 'duo') {
            $kd    = $stat->duo_matches > 0 ? $stat->duo_kills / $stat->duo_matches : 0;
            $score = $stat->duo_matches > 0 ? $stat->duo_score / $stat->duo_matches : 0;
        } else {
            $kd    = $stat->squad_matches > 0 ? $stat->squad_kills / $stat->squad_matches : 0;
            $score = $stat->squad_matches > 0 ? $stat->squad_score / $stat->squad_matches : 0;
        }

        $firstKill  = $kd / 1 * 0.3;
        $firstKill  = $firstKill > 0.3 ? 0.3 : $firstKill;
        $firstKill  = $firstKill < 0 ? 0 : $firstKill;
        $secondKill = ($kd - 1) / 4 * 0.45;
        $secondKill = $secondKill > 0.45 ? 0.45 : $secondKill;
        $secondKill = $secondKill < 0 ? 0 : $secondKill;
        $thirdKill  = ($kd - 5) / 5 * 0.20;
        $thirdKill  = $thirdKill > 0.20 ? 0.20 : $thirdKill;
        $thirdKill  = $thirdKill < 0 ? 0 : $thirdKill;
        $fourthKill = ($kd - 10) / 90 * 0.05;
        $fourthKill = $fourthKill > 0.05 ? 0.05 : $fourthKill;
        $fourthKill = $fourthKill < 0 ? 0 : $fourthKill;

        $firstScore  = min(1, $score / 500) * 0.95;
        $secondScore = $score > 500 ? ($score - 500) / 2000 * 0.05 : 0;

        return ($firstKill + $secondKill + $thirdKill + $fourthKill) * $killPoints + ($firstScore * $secondScore) * $scorePoints;
    }
}
