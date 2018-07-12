<?php namespace App\Models\Fortnite;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class FortniteStatDiff extends Model {

    protected $fillable = [
        'user_id', 'solo_matches', 'solo_wins', 'solo_kills', 'duo_matches', 'duo_wins', 'duo_kills',
        'squad_matches', 'squad_wins', 'squad_kills', 'solo_mmr', 'duo_mmr', 'squad_mmr', 'solo_score',
        'duo_score', 'squad_score'
    ];
}