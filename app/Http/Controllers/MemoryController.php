<?php namespace App\Http\Controllers;

use App\Constants;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Memory;
use App\Models\Project;
use App\Models\Thought;
use Illuminate\Http\Request;
use App\Models\ContentQuery;
use Illuminate\Http\Response;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;

class MemoryController extends Controller {

    public function home() {

        $memories = Memory::orderBy('memory_date', 'asc')->get();

        $return = [];
        foreach ($memories as $memory) {
            $memory->memory_date = date('Y-m-d', strtotime($memory->memory_date));
            $return[] = $memory;
        }

        return [
            'memories' => $return
        ];
    }

    public function getMemory(Request $request, $id) {

        $memory = Memory::find($id);

        return [
            'memory' => $memory
        ];
    }

    public function insertMemory(Request $request) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();

        unset($data['htmlContent']);

        if (!$user) {
            return ['status' => false];
        }

        $memory = new Memory($data);
        $memory->save();

        return [
            'message' => 'Success',
            'url'     => '/memory/' . $memory->id
        ];
    }

    public function updateMemory(Request $request, $id) {

        date_default_timezone_set(Constants::$CURRENT_TIMEZONE);
        $user = Auth::user();
        $data = $request->all();

        $memory = Memory::find($id);

        unset($data['htmlContent']);

        if (!$user) {
            return ['status' => false];
        }

        foreach ($data as $key => $value) {
            $memory->{$key} = $value;
        }

        $memory->save();

        return [
            'message' => 'Success'
        ];
    }
}