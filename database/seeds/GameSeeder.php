<?php
use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\User;
use App\Models\ContentQuery;

class GameSeeder extends Seeder {

    public function run() {

        date_default_timezone_set(\App\Constants::$CURRENT_TIMEZONE);

        $smallImages = [
            'http://assets1.ignimgs.com/2017/06/13/marioodyssey-1280-1497373316757_1280w.jpg',
            'https://cdn3.twinfinite.net/wp-content/uploads/2017/03/Zelda-Breath-of-the-Wild-horses.jpg',
            'https://gematsu.com/wp-content/uploads/2017/06/Xenoblade-2-Gameplay_06-13-17.jpg',
            'https://content.ebgames.com.au/website/videos/images/screenshots/208753_screenshot_05_l.jpg',
            'https://i.ytimg.com/vi/SjKKbFe61GQ/maxresdefault.jpg'
        ];
        $largeImages = [
            'https://mfiles.alphacoders.com/701/701741.jpg',
            'https://pm1.narvii.com/6375/2797169dfbb3b110b085a4a8b90cac70d177163b_hq.jpg',
            'https://now.estarland.com/images/products/50/53950/162755.jpg',
            'https://www.gamewallpapers.com/img_script/mobile_dir2/img.php?src=wallpaper_nier_automata_02_1440x2560.jpg&width=450&height=800&crop-to-fit',
            'https://fsb.zobj.net/crop.php?r=ZZQzSdz3sG2g0dm3v0GOHcXDeLRLkeLIlBn7TCJlCwv2_0621ydLEkX6ts0CCo0PPd1XuOy9FRH7jndCHdzoRTNmG_mTuD8S8MxRPN71Pcd-5-aiA2p_fsaRWNSBObdCvbWpki348TN__JnZK61YLZaTKRJccgD0hyvlyw'
        ];


        $game = new Game([
            "name"              => 'Super Mario Odyssey',
            "description"       => "The next installment of the beloved mario franchise makes its way to the switch with 
                a large emphasis on nostalgia and fun gameplay. Bowser has kidnapped Princess Peach again and is preparing
                for their perfect wedding as mario chase them around the world with the help of his new friend Cappy.",
            "release_date"      => "2017-10-27",
            "system"            => "Nintendo Switch",
            "score"             => 97,
            'image_url'         => $smallImages[0],
            'large_image_url'   => $largeImages[0],
            'currently_playing' => 1,
            'time_to_beat'      => 12,
            'playtime'          => 30,
            'featured'          => 1,
            'tag'               => 'Platformer',
            "review"            => "{
                \"entityMap\": {},
                \"blocks\": [{
                    \"key\": \"637gr\",
                    \"text\": \"\",
                    \"type\": \"unstyled\",
                    \"depth\": 0,
                    \"inlineStyleRanges\": [{
                        \"length\": 13,
                        \"offset\": 0,
                        \"style\": \"fontsize-18\"
                    }],
                    \"entityRanges\": [],
                    \"data\": {}
                }]
            }",
        ]);
        $game->save();

        ContentQuery::create([
            'type'          => 'game',
            'item_id'       => $game->id,
            'featured'      => 1,
            'thumbnail_url' => $game->image_url,
            'tag'           => $game->tag,
            'headline'      => $game->name,
            'description'   => $game->description,
            'meta_data_1'   => $game->release_date,
            'meta_data_2'   => $game->score,
            'created_at'    => date('Y-m-d H:i:s', time() - rand(0, 1000) * 60)
        ]);


        $game = new Game([
            "name"              => 'The Legend of Zelda: Breathe of the Wild',
            "description"       => "100 years have passed since Ganon invaded Hyrule and defeated Link and the 4 great heroes.
            Awakening from being sealed away, Link must rescue the 4 great beasts and defeat Ganon in this large open world game.",
            "release_date"      => "2017-03-03",
            "system"            => "Nintendo Switch",
            "score"             => 98,
            'image_url'         => $smallImages[1],
            'large_image_url'   => $largeImages[1],
            'currently_playing' => 1,
            'time_to_beat'      => 12,
            'featured'          => 1,
            'playtime'          => 30,
            'tag'               => 'Adventure'
        ]);
        $game->save();

        ContentQuery::create([
            'type'          => 'game',
            'item_id'       => $game->id,
            'featured'      => 1,
            'thumbnail_url' => $game->image_url,
            'tag'           => $game->tag,
            'headline'      => $game->name,
            'description'   => $game->description,
            'meta_data_1'   => $game->release_date,
            'meta_data_2'   => $game->score,
            'created_at'    => date('Y-m-d H:i:s', time() - rand(0, 1000) * 60)
        ]);


        $game = new Game([
            "name"              => 'Xenoblade Chronicles 2',
            "description"       => "After being resurrected by Pyra, Rex wants to fulfill her wish of returning the Ellysium in 
            war striken world of Alrest. Xenoblade is a story driven JRPG with a massive world and unique battle mechanics that take time to master.",
            "release_date"      => "2017-12-01",
            "system"            => "Nintendo Switch",
            "score"             => null,
            'image_url'         => $smallImages[2],
            'large_image_url'   => $largeImages[2],
            'featured'          => 1,
            'currently_playing' => 0,
            'time_to_beat'      => null,
            'playtime'          => null,
            'tag'               => 'JRPG'
        ]);
        $game->save();

        ContentQuery::create([
            'type'          => 'game',
            'item_id'       => $game->id,
            'featured'      => 1,
            'thumbnail_url' => $game->image_url,
            'tag'           => $game->tag,
            'headline'      => $game->name,
            'description'   => $game->description,
            'meta_data_1'   => $game->release_date,
            'meta_data_2'   => $game->score,
            'created_at'    => date('Y-m-d H:i:s', time() - rand(0, 1000) * 60)
        ]);


        $game = new Game([
            "name"              => 'NieR: Automata',
            "description"       => "In the future were humanity went nearly extinct and fled to the moon following an alien invasion, 
            humanity is reliant on androids to eliminate the remaining mechanical forces on Earth so they can return. 2B, the protagonist, 
            is an android learning the mysteries of the creatures left back on Earth.",
            "release_date"      => "2017-02-23",
            "system"            => "PC",
            "score"             => null,
            'image_url'         => $smallImages[3],
            'large_image_url'   => $largeImages[3],
            'featured'          => 1,
            'currently_playing' => 1,
            'time_to_beat'      => null,
            'playtime'          => 4,
            'tag'               => 'JRPG'
        ]);
        $game->save();

        ContentQuery::create([
            'type'          => 'game',
            'item_id'       => $game->id,
            'featured'      => 1,
            'thumbnail_url' => $smallImages[3],
            'tag'           => $game->tag,
            'headline'      => $game->name,
            'description'   => $game->description,
            'meta_data_1'   => $game->release_date,
            'meta_data_2'   => $game->score,
            'created_at'    => date('Y-m-d H:i:s', time() - rand(0, 1000) * 60)
        ]);


        $game = new Game([
            "name"              => "PlayerUnknown's Battlegrounds",
            "description"       => "A battle royale multiplayer shooter where you are 1 out of 100 people dropped out of an island 
            where there is only 1 winner. Kill, sneak, and sprint to the end and be the last one standing to take home that
            sweet chicken dinner.",
            "release_date"      => "2017-03-20",
            "system"            => "PC",
            "score"             => 90,
            'image_url'         => $smallImages[4],
            'large_image_url'   => $largeImages[4],
            'featured'          => 1,
            'currently_playing' => 1,
            'time_to_beat'      => null,
            'playtime'          => 270,
            'tag'               => 'Battle Royale Shooter'
        ]);
        $game->save();

        ContentQuery::create([
            'type'          => 'game',
            'item_id'       => $game->id,
            'featured'      => 1,
            'thumbnail_url' => $game->image_url,
            'tag'           => $game->tag,
            'headline'      => $game->name,
            'description'   => $game->description,
            'meta_data_1'   => $game->release_date,
            'meta_data_2'   => $game->score,
            'created_at'    => date('Y-m-d H:i:s', time() - rand(0, 1000) * 60)
        ]);
    }
}