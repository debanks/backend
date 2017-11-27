<?php namespace App\Http\Controllers;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Project;
use App\Models\Thought;
use Illuminate\Http\Request;
use App\Models\ContentQuery;
use Illuminate\Http\Response;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;

class ContentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {

        $featured = $request->input('featured', null);
        $type     = $request->input('type', null);
        $query    = $request->input('query', null);
        $page     = $request->input('page', 1);

        $select = "content_query.*";

        if ($query !== null && $query != "") {
            $contentQuery = ContentQuery::whereRaw("MATCH(tag, headline, description, content, link_item_name) AGAINST ('+$query*' IN BOOLEAN MODE)");
            $select       .= ",MATCH(tag, headline, description, content, link_item_name) AGAINST ('+$query*' IN BOOLEAN MODE) * 1000 as score";
        } else {
            $contentQuery = ContentQuery::orderBy("created_at", "desc");
        }

        if ($featured !== null) {
            $contentQuery->where('featured', '=', $featured);
        }

        if ($type !== null && $type != "") {
            $contentQuery->where('type', '=', $type);
        }

        $countQuery = $contentQuery;
        $counts     = $countQuery->select(\DB::raw("count(*) as count"))->get();

        $contentQuery->select(\DB::raw($select))->limit(25)->skip(($page - 1) * 25);

        if ($query !== null && $query != "") {
            $contentQuery->orderBy("score", "desc")->orderBy('created_at', 'desc');
        }

        $content = $contentQuery->get();

        return response([
            'content' => $content,
            'count'   => $counts[0]->count
        ], 200);
    }

    public function header() {

        $items = ContentQuery::whereIn('type', ['game', 'project'])->orderBy('headline', 'asc')->get();
        return ['items' => $items];
    }

    public function home() {

        $top      = ContentQuery::where('featured', '=', 1)->orderBy('created_at', 'desc')->limit(1)->get();
        $articles = ContentQuery::where('featured', '=', 1)->where('type', '=', 'article')->orderBy('created_at', 'desc')->limit(4)->get();
        $projects = ContentQuery::where('featured', '=', 1)->where('type', '=', 'project')->orderBy('meta_data_2', 'desc')->limit(4)->get();

        return [
            'top'      => $top[0],
            'articles' => $articles,
            'projects' => $projects
        ];
    }

    public function programming() {

        $projects = Project::orderBy('end_date', 'desc')->get();
        $content  = ContentQuery::where('tag', '=', 'Project')->whereIn('type', ['article', 'thought'])->orderBy('created_at', 'desc')->limit(8)->get();

        return [
            'projects' => $projects,
            'content'  => $content
        ];
    }

    public function games() {

        $playing = Game::where('currently_playing', '=', 1)->orderBy('release_date', 'desc')->get();
        $all     = Game::orderBy('release_date', 'desc')->get();
        $content = ContentQuery::where('tag', '=', 'Game')->whereIn('type', ['article', 'thought'])->orderBy('created_at', 'desc')->limit(8)->get();

        return [
            'games'   => $all,
            'playing' => $playing,
            'content' => $content
        ];
    }

    public function singleGame(Request $request, $id) {

        $game    = Game::find($id);
        $content = ContentQuery::where('link_item_type', '=', 'game')->where('link_item_id', '=', $id)->orderBy('created_at', 'desc')->limit(12)->get();

        return [
            'game'    => $game,
            'content' => $content
        ];
    }

    public function singleProject(Request $request, $name) {

        $project = Project::where('name', '=', $name)->first();
        $content = ContentQuery::where('link_item_type', '=', 'project')->where('link_item_id', '=', $project->id)->orderBy('created_at', 'desc')->limit(12)->get();

        return [
            'project' => $project,
            'content' => $content
        ];
    }

    public function getArticle(Request $request, $id) {

        $article = Article::find($id);
        $item    = null;

        if ($article->item_type !== null) {
            $item = ContentQuery::where('type', '=', $article->item_type)->where('item_id', '=', $article->item_id)->first();
        }

        return [
            'article' => $article,
            'item'    => $item
        ];
    }

    public function insertThought(Request $request) {

        $user = Auth::user();
        $data = $request->all();

        if (!$user || !isset($data['thought']) || $data['thought'] === "") {
            return ['status' => false];
        }

        if (isset($data['item'])) {
            $splits = explode('_', $data['item']);
            if (count($splits) === 2) {
                $data['item_type'] = $splits[0];
                $data['item_id']   = $splits[1];
                $item              = $data['item_type'] === 'game' ? Game::find($data['item_id']) : Project::find($data['item_id']);
                $data['item_name'] = $item ? $item->name : null;
            }
            unset($data['item']);
        }

        $thought = new Thought([
            'thought'   => $data['thought'],
            'image_url' => isset($data['image_url']) ? $data['image_url'] : null,
            'item_id'   => isset($data['item_id']) ? $data['item_id'] : null,
            'item_type' => isset($data['item_type']) ? $data['item_type'] : null,
            'item_name' => isset($data['item_name']) ? $data['item_name'] : null,
        ]);
        $thought->save();

        ContentQuery::create([
            'type'           => 'thought',
            'item_id'        => $thought->id,
            'description'    => $thought->thought,
            'thumbnail_url'  => $thought->image_url,
            'featured'       => 0,
            'tag'            => $thought->item_type ? strtoupper($thought->item_type) : "General",
            'created_at'     => $thought->created_at,
            'link_item_type' => isset($data['item_type']) ? $data['item_type'] : null,
            'link_item_id'   => isset($data['item_id']) ? $data['item_id'] : null,
            'link_item_name' => isset($data['item_name']) ? $data['item_name'] : null,
        ]);

        return [
            'status' => true,
            'url'    => '/thought/' . $thought->id . '/' . $user->user_name
        ];
    }

    public function insertArticle(Request $request) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();
        $text = strip_tags($data['htmlContent']);

        unset($data['htmlContent']);

        if (!$user) {
            return ['status' => false];
        }

        if (isset($data['item'])) {
            $splits = explode('_', $data['item']);
            if (count($splits) === 2) {
                $data['item_type'] = $splits[0];
                $data['item_id']   = $splits[1];
                $item              = $data['item_type'] === 'game' ? Game::find($data['item_id']) : Project::find($data['item_id']);
                $data['item_name'] = $item ? $item->name : null;
            }
            unset($data['item']);
        }

        $article          = new Article($data);
        $article->user_id = $user->id;
        $article->save();

        ContentQuery::create([
            'type'           => 'article',
            'item_id'        => $article->id,
            'headline'       => $article->title,
            'description'    => $article->summary,
            'thumbnail_url'  => $article->thumbnail_url,
            'featured'       => $article->featured,
            'created_at'     => $article->created_at,
            'content'        => $text,
            'tag'            => $article->tag,
            'link_item_type' => isset($data['item_type']) ? $data['item_type'] : null,
            'link_item_id'   => isset($data['item_id']) ? $data['item_id'] : null,
            'link_item_name' => isset($data['item_name']) ? $data['item_name'] : null,
        ]);

        return [
            'message' => 'Success',
            'url'     => '/article/' . $article->id . '/' . $article->title
        ];
    }

    public function insertGame(Request $request) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();
        $text = strip_tags($data['htmlContent']);

        unset($data['htmlContent']);

        if (!$user) {
            return ['status' => false];
        }

        $game = new Game($data);
        $game->save();

        ContentQuery::create([
            'type'          => 'game',
            'item_id'       => $game->id,
            'headline'      => $game->name,
            'description'   => $game->description,
            'thumbnail_url' => $game->image_url,
            'featured'      => $game->featured,
            'created_at'    => $game->created_at,
            'meta_data_1'   => $game->release_date,
            'meta_data_2'   => $game->score,
            'content'       => $text,
            'tag'           => $game->tag
        ]);

        return [
            'message' => 'Success',
            'url'     => '/game/' . $game->id . '/' . $game->name
        ];
    }

    public function insertProject(Request $request) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();
        $text = strip_tags($data['htmlContent']);

        unset($data['htmlContent']);

        if (!$user) {
            return ['status' => false];
        }

        $project = new Project($data);
        $project->save();

        ContentQuery::create([
            'type'          => 'project',
            'item_id'       => $project->id,
            'headline'      => $project->name,
            'description'   => $project->description,
            'thumbnail_url' => $project->image_url,
            'featured'      => $project->featured,
            'created_at'    => $project->created_at,
            'meta_data_1'   => $project->start_date,
            'meta_data_2'   => $project->end_date,
            'content'       => $text,
            'tag'           => $project->tag
        ]);

        return [
            'message' => 'Success',
            'url'     => '/programming/' . $project->name
        ];
    }

    public function updateArticle(Request $request, $id) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();
        $text = strip_tags($data['htmlContent']);

        $article = Article::find($id);

        unset($data['htmlContent']);

        if (!$user) {
            return ['status' => false];
        }

        if (isset($data['item'])) {
            $splits = explode('_', $data['item']);
            if (count($splits) === 2) {
                $data['item_type'] = $splits[0];
                $data['item_id']   = $splits[1];
                $item              = $data['item_type'] === 'game' ? Game::find($data['item_id']) : Project::find($data['item_id']);
                $data['item_name'] = $item ? $item->name : null;
            }
            unset($data['item']);
        }

        foreach ($data as $key => $value) {
            $article->{$key} = $value;
        }

        $article->save();

        ContentQuery::where('type', '=', 'article')->where('item_id', '=', $id)->update([
            'headline'       => $article->title,
            'description'    => $article->summary,
            'thumbnail_url'  => $article->thumbnail_url,
            'featured'       => $article->featured,
            'content'        => $text,
            'link_item_type' => isset($data['item_type']) ? $data['item_type'] : null,
            'link_item_id'   => isset($data['item_id']) ? $data['item_id'] : null,
            'link_item_name' => isset($data['item_name']) ? $data['item_name'] : null,
            'tag'            => $article->tag
        ]);

        return [
            'message' => 'Success',
            'url'     => '/article/' . $article->id . '/' . $article->title
        ];
    }

    public function updateGame(Request $request, $id) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();
        $text = strip_tags($data['htmlContent']);

        $game = Game::find($id);

        unset($data['htmlContent']);

        if (!$user) {
            return ['status' => false];
        }

        foreach ($data as $key => $value) {
            $game->{$key} = $value;
        }

        $game->save();

        ContentQuery::where('type', '=', 'game')->where('item_id', '=', $id)->update([
            'headline'       => $game->name,
            'description'    => $game->description,
            'thumbnail_url'  => $game->image_url,
            'featured'       => $game->featured,
            'meta_data_1'    => $game->release_date,
            'meta_data_2'    => $game->score,
            'content'        => $text,
            'link_item_type' => isset($data['item_type']) ? $data['item_type'] : null,
            'link_item_id'   => isset($data['item_id']) ? $data['item_id'] : null,
            'link_item_name' => isset($data['item_name']) ? $data['item_name'] : null,
            'tag'            => $game->tag
        ]);

        return [
            'message' => 'Success',
            'url'     => '/game/' . $game->id . '/' . $game->name
        ];
    }

    public function updateProject(Request $request, $id) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();
        $text = strip_tags($data['htmlContent']);

        $project = Project::find($id);

        unset($data['htmlContent']);

        if (!$user) {
            return ['status' => false];
        }

        foreach ($data as $key => $value) {
            $project->{$key} = $value;
        }

        $project->save();

        ContentQuery::where('type', '=', 'project')->where('item_id', '=', $id)->update([
            'headline'       => $project->name,
            'description'    => $project->description,
            'thumbnail_url'  => $project->image_url,
            'featured'       => $project->featured,
            'meta_data_1'    => $project->start_date,
            'meta_data_2'    => $project->end_date,
            'content'        => $text,
            'link_item_type' => isset($data['item_type']) ? $data['item_type'] : null,
            'link_item_id'   => isset($data['item_id']) ? $data['item_id'] : null,
            'link_item_name' => isset($data['item_name']) ? $data['item_name'] : null,
            'tag'            => $project->tag
        ]);

        return [
            'message' => 'Success',
            'url'     => '/project/' . $project->name
        ];
    }

    public function image(Request $request) {

        $bucket  = 'davis-images';
        $homeUrl = 'https://s3.us-east-2.amazonaws.com/davis-images';
        $article = $request->input('article', 0);

        $user = Auth::user();
        if (!$user || !$request->hasFile('image') || !$request->file('image')->isValid()) {
            return [
                'status' => false
            ];
        }

        $file      = $request->file('image');
        $path      = $file->path();
        $name      = preg_replace("/[^a-z0-9\.]/", "", strtolower($file->getClientOriginalName()));
        $extension = $file->extension();
        $splits    = explode(".", $name);

        $name = $splits[0] . '_' . strtolower($user->user_name) . '_' . date('Y-m-d_His') . '.' . $extension;

        $client = new S3Client([
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY'),
                'secret' => env('AWS_SECRET_KEY'),
            ],
            'region'      => 'us-east-2',
            'version'     => 'latest'
        ]);

        $result = $client->putObject([
            'Bucket'   => $bucket,
            'Key'      => $name,
            'Body'     => fopen($path, 'r'),
            'ACL'      => 'public-read',
            'Metadata' => [
                'user'    => $user->user_name,
                'user_id' => $user->id
            ]
        ]);

        $client->waitUntil('ObjectExists', [
            'Bucket' => $bucket,
            'Key'    => $name
        ]);

        $url = $homeUrl . '/' . $name;

        if ($article == 1) {
            return ['data' => ['link' => $url]];
        } else {
            return [
                'url' => $url
            ];
        }
    }
}