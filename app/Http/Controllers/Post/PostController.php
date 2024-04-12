<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware('permission:show_post')->only(['index', 'show']);
    }
    
    public function index()
    {
        if (Post::exists()) {
            $posts = Post::with(['doctor' => function ($query) {
                $query->select('id', 'name');
            }])->paginate();
            return $this->paginateResponse(PostResource::collection($posts));
        }
        return $this->errorResponse('data not found');
    }
    public function show($id)
    {
        $post = Post::with(['doctor' => function ($query) {
            $query->select('id', 'name');
        }])->find($id);
        if ($post) {
            return $this->okResponse('data fetched successfully',PostResource::make($post));
        }
        return $this->errorResponse('data not found');
    }
    public function store(StorePostRequest $request)
    {
        //
    }
    
    public function update(UpdatePostRequest $request,$id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
}
