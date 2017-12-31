<?php
use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Article;
use App\Models\Thought;
use App\Models\User;
use App\Models\Project;
use App\Models\ContentQuery;
use App\Models\Memory;

class ArticleSeeder extends Seeder {

    public $images = [
        "http://cdn.cgmagonline.com/wp-content/uploads/2017/03/nier-automata-revew-the-new-gold-standard-1068x601.jpg",
        "https://www.dualshockers.com/wp-content/uploads/2016/04/xenoblade-chronicles-3d-123122.jpg",
        "https://media2.nintendowire.com/wp-content/uploads/2017/01/XenobladeChronicles2-Switch-Field.jpg",
        "http://www.zelda.com/breath-of-the-wild/assets/media/header/Main-Day.jpg",
        "https://nintendo.corednacdn.com/prodcatalogue/product/4196/screenshot/13660.jpg",
        "https://vignette2.wikia.nocookie.net/codegeass/images/8/82/Code.Geass_.Boukoku.no.Akito.full.1898992.jpg/revision/latest/scale-to-width-down/2000?cb=20150717023546",
        "https://i.ytimg.com/vi/aNQvLECht08/maxresdefault.jpg"
    ];

    public function run() {

        date_default_timezone_set(\App\Constants::$CURRENT_TIMEZONE);
        $faker    = Faker\Factory::create();
        $games    = Game::all();
        $projects = Project::all();

        for ($i = 0; $i < 25; $i++) {
            $article = new Article([
                "title"         => $faker->words(rand(3, 8), true),
                "thumbnail_url" => $this->images[$i % 7],
                "summary"       => $faker->sentences(rand(3, 6), true),
                "content"       => "{
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
                "featured"      => 1,
                "link_url"      => "http://davisbanks.com",
                "created_at"    => date("Y-m-d H:i:s", time() - rand(0, 120) * 60)
            ]);

            $typeRand = rand(1, 10);
            if ($typeRand < 7 && $typeRand < 9) {
                $rand               = rand(0, count($games) - 1);
                $article->item_name = $games[$rand]->name;
                $article->item_id   = $games[$rand]->id;
                $article->item_type = 'game';
                $article->tag       = 'Gaming';
            } elseif ($typeRand > 8) {
                $rand               = rand(0, count($projects) - 1);
                $article->item_name = $projects[$rand]->name;
                $article->item_id   = $projects[$rand]->id;
                $article->item_type = 'project';
                $article->tag       = 'Project';
            }
            $article->save();

            ContentQuery::create([
                "tag"            => $article->tag,
                'type'           => 'article',
                'item_id'        => $article->id,
                'headline'       => $article->title,
                'description'    => $article->summary,
                'thumbnail_url'  => $article->thumbnail_url,
                'featured'       => $article->featured,
                'created_at'     => $article->created_at,
                'link_item_type' => $article->item_type,
                'link_item_id'   => $article->item_id,
                'link_item_name' => $article->item_name
            ]);

            Memory::create([
                "title"         => $faker->words(rand(3, 8), true),
                "thumbnail_url" => $this->images[$i % 7],
                "summary"       => $faker->sentences(rand(3, 6), true),
                "memory_date"   => date('Y-m-d', time() - rand(0, 700) * 60 * 60 * 24),
                "content"       => "{
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
                }"
            ]);
        }

        $images = [
            'http://www.roc-search.com/File.ashx?path=Root/Images/KnowledgeCentre/AAEAAQAAAAAAAA3EAAAAJDgxYjY2MTczLTFjZTUtNDZlOC1hZjBlLTc5OTM1ZjdlYTY3OQ.png',
            'https://www.roberthalf.com.sg/sites/roberthalf.com.sg/files/best-programming-language-sg.jpg'
        ];

        for ($i = 0; $i < 25; $i++) {
            $thought = new Thought([
                'thought'    => $faker->sentences(rand(2, 3), true),
                'featured'   => 1,
                "created_at" => date("Y-m-d H:i:s", time() - rand(0, 120) * 60)
            ]);

            $typeRand = rand(0, 4);
            if ($typeRand == 2) {
                $thought->image_url = $this->images[rand(0, 6)];
            }

            if ($typeRand == 3) {
                $rand               = rand(0, count($games) - 1);
                $thought->item_name = $games[$rand]->name;
                $thought->item_id   = $games[$rand]->id;
                $thought->item_type = 'game';
                $thought->tag       = 'Gaming';
            } elseif ($typeRand > 8) {
                $rand               = rand(0, count($projects) - 1);
                $thought->item_name = $projects[$rand]->name;
                $thought->item_id   = $projects[$rand]->id;
                $thought->item_type = 'project';
                $thought->tag       = 'Project';
            }

            $thought->save();

            ContentQuery::create([
                'type'           => 'thought',
                'item_id'        => $thought->id,
                'description'    => $thought->thought,
                'thumbnail_url'  => $thought->image_url,
                'featured'       => $thought->featured,
                'created_at'     => $thought->created_at,
                'link_item_type' => $thought->item_type,
                'link_item_id'   => $thought->item_id,
                'link_item_name' => $thought->item_name,
                'tag'            => $thought->tag
            ]);
        }
    }
}