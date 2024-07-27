<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Services\DatabaseService;
use App\Models\Post;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private $databaseService;
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function index(Request $request) {
        $country = $request->header('country');
        $keyword = $request -> input('keyword');
        $cacheKey = 'posts_' . $country . '_' . md5($keyword);

        $data = Cache::get($cacheKey);

        if ($data) {
            return response()->json(['data' => $data]);
        }
        
        $connection = $this->databaseService->getConnectionBasedCountry($country);

        if (!$connection) {
           return response()->json(['error' => 'Invalid country header'], 400); 
        }

        $posts = DB::connection($connection)->table('posts');

        // For Search
        if($keyword){
            $posts = $posts->where('title', 'like', '%' . $keyword . '%');
        }

        $data = $posts->get();

        Cache::put($cacheKey, $data, 3600);

        return response()->json(['data' => $data]);
    }

    public function store(StorePostRequest $request)  {
        $user = Auth::user();

        $country = $request->header('country');
        $connection = $this->databaseService->getConnectionBasedCountry($country);

        if (!$connection) {
           return response()->json(['error' => 'Invalid country header'], 400); 
        }

        $data = $request->validated();
        $data['user_id'] = $user->id;

        $post = Post::on($connection)->create($data);

        return response()->json(['data' => $post]);
    }
}
