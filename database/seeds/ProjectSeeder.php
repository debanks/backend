<?php
use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Article;
use App\Models\Thought;
use App\Models\User;
use App\Models\Project;
use App\Models\ContentQuery;

class ProjectSeeder extends Seeder {

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

        $project = new Project([
            "name"            => 'Scicrunch',
            "image_url"       => "/images/scicrunch.png",
            "large_image_url" => "/images/scicrunch.png",
            "description"     => "Scicrunch is a neuroscience community creation portal with access to several neuroscience
            databases to access and show relevant to your community.",
            'employer'        => 'Neuroscience Information Framework',
            "featured"        => 1,
            'start_date'      => '2012-07-01',
            "about"           => "{
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
            'end_date'        => '2015-03-01',
            'color'           => '#013c41',
            'tag'             => 'Web Dev',
            'languages'       => "php,html,css",
            "created_at"      => date("Y-m-d H:i:s", time() - rand(0, 120) * 60)
        ]);

        $project->save();

        ContentQuery::create([
            'type'          => 'project',
            'tag'           => $project->tag,
            'item_id'       => $project->id,
            'headline'      => $project->name,
            'description'   => $project->description,
            'thumbnail_url' => $project->image_url,
            'featured'      => $project->featured,
            'created_at'    => $project->created_at,
            'meta_data_1'   => $project->start_date,
            'meta_data_2'   => $project->end_date
        ]);

        $project = new Project([
            "name"            => 'Stampede',
            "image_url"       => "/images/stampede.png",
            "large_image_url" => "/images/stampede.png",
            "description"     => "Stampede is a marketing campaign creation software for creating and deploying ads
            to several different platforms.",
            'employer'        => 'Underground Elephant',
            "featured"        => 1,
            "color"           => '#074275',
            'start_date'      => '2015-03-01',
            'end_date'        => '2017-07-01',
            'tag'             => 'Web Dev',
            'languages'       => "php,angular,sass,laravel",
            "created_at"      => date("Y-m-d H:i:s", time() - rand(0, 120) * 60)
        ]);

        $project->save();

        ContentQuery::create([
            'type'          => 'project',
            'tag'           => $project->tag,
            'item_id'       => $project->id,
            'headline'      => $project->name,
            'description'   => $project->description,
            'thumbnail_url' => $project->image_url,
            'featured'      => $project->featured,
            'created_at'    => $project->created_at,
            'meta_data_1'   => $project->start_date,
            'meta_data_2'   => $project->end_date
        ]);

        $project = new Project([
            "name"            => 'AdHive',
            "image_url"       => "/images/adhive.png",
            "large_image_url" => "/images/adhive.png",
            "description"     => "Stampede is a marketing campaign management software for monitoring and automated campaign
            management to pause, start, and push out ads based on statistics.",
            "featured"        => 1,
            'color'           => '#d35400',
            'start_date'      => '2015-05-01',
            'end_date'        => '2017-07-01',
            'employer'        => 'Underground Elephant',
            'tag'             => 'Web Dev',
            'languages'       => "php,angular,sass,symfony",
            "created_at"      => date("Y-m-d H:i:s", time() - rand(0, 120) * 60)
        ]);

        $project->save();

        ContentQuery::create([
            'type'          => 'project',
            'tag'           => $project->tag,
            'item_id'       => $project->id,
            'headline'      => $project->name,
            'description'   => $project->description,
            'thumbnail_url' => $project->image_url,
            'featured'      => $project->featured,
            'created_at'    => $project->created_at,
            'meta_data_1'   => $project->start_date,
            'meta_data_2'   => $project->end_date
        ]);

        $project = new Project([
            "name"            => 'Smaug',
            "image_url"       => "/images/smaug.png",
            "large_image_url" => "/images/smaug.png",
            "description"     => "Smaug is an automated company finance suite to connect internal data to quickbooks for 
            fast invoicing. With several alerts, it was easy to manage incoming invoices and payments.",
            "featured"        => 1,
            'start_date'      => '2016-11-01',
            'end_date'        => '2017-03-01',
            'employer'        => 'Underground Elephant',
            'tag'             => 'Web Dev',
            'color'           => '#ca4247',
            'languages'       => "php,angular,sass,laravel,quickbooks",
            "created_at"      => date("Y-m-d H:i:s", time() - rand(0, 120) * 60)
        ]);

        $project->save();

        ContentQuery::create([
            'type'          => 'project',
            'tag'           => $project->tag,
            'item_id'       => $project->id,
            'headline'      => $project->name,
            'description'   => $project->description,
            'thumbnail_url' => $project->image_url,
            'featured'      => $project->featured,
            'created_at'    => $project->created_at,
            'meta_data_1'   => $project->start_date,
            'meta_data_2'   => $project->end_date
        ]);

        $project = new Project([
            "name"            => 'SpirAI',
            "image_url"       => "/images/spirai.png",
            "large_image_url" => "/images/spirai.png",
            "description"     => "SpirAI is a cryptocurrency hub to view statistics and research new cryptocoins. The site
            aims to be the central place for people to talk and learn about the cryptocurrency world.",
            "featured"        => 1,
            'start_date'      => '2017-07-01',
            'end_date'        => '2018-06-01',
            'employer'        => 'Self',
            'tag'             => 'Web Dev',
            'color'           => '#323748',
            'languages'       => "php,react,sass,symfony",
            "created_at"      => date("Y-m-d H:i:s", time() - rand(0, 120) * 60)
        ]);

        $project->save();

        ContentQuery::create([
            'type'          => 'project',
            'tag'           => $project->tag,
            'item_id'       => $project->id,
            'headline'      => $project->name,
            'description'   => $project->description,
            'thumbnail_url' => $project->image_url,
            'featured'      => $project->featured,
            'created_at'    => $project->created_at,
            'meta_data_1'   => $project->start_date,
            'meta_data_2'   => $project->end_date
        ]);
    }
}