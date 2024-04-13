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
        $this->middleware('permission:store_post')->only(['store']);
        $this->middleware('permission:update_post')->only(['update']);
        $this->middleware('permission:delete_post')->only(['destroy']);
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
        $data = $request->validated();
        $post = Post::create($data);
        if($request->hasFile('image')) {
            $post->addMediaFromRequest('image')->toMediaCollection('post_image');
        }
        return $this->createResponse(PostResource::make($post));
    }
    
    public function update(UpdatePostRequest $request,$id)
    {
        $data = $request->validated();
        $post = Post::find($id);
        if($post) {
            $post->update($data);
            if ($request->hasFile('image')) {
                $post->clearMediaCollection('post_image');
                $post->addMediaFromRequest('image')->toMediaCollection('post_image');
            }
            return $this->okResponse('record updated',PostResource::make($post));
        }
        return $this->errorResponse('record not found');
    }
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->delete();
            return $this->okResponse('record deleted',[]);
        }
        return $this->errorResponse('record not found');
    }
}
