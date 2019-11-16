<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Post::paginate(10), 200);
    }

    /**
     * @param $title
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($title)
    {
        return response()->json(Post::where('title', $this->slug($title))->first(), 200);
    }

    /**
     * @param CreatePostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreatePostRequest $request)
    {
        return response()->json(Post::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'slug' => $this->slug($request->get('title'), true),
            'user_id' => Auth::user()->id,
            'image' => $request->get('image'),
        ]), 201);
    }

    /**
     * @param Request $request
     * @param         $title
     * @return \Illuminate\Http\JsonResponse
     */
    public function patch(Request $request, $title)
    {
        $post = Post::where('title', $this->slug($title))->first();
        $post->title = $request->get('title');
        $post->slug = $this->slug($request->get('title'), true);
        $post->save();

        return response()->json($post, 200);
    }

    /**
     * @param $title
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($title)
    {
        if (Post::where('title', $this->slug($title))->delete()) {
            return response()->json(['status' => 'record deleted successfully'], 200);
        }

        return response()->json(['status' => 'something went wrong'], 500);
    }

    /**
     * @param      $text
     * @param bool $fromSpace
     * @return mixed
     */
    protected function slug($text, $fromSpace = false)
    {
        return $fromSpace ? str_replace(' ', '-', $text) : str_replace('-', ' ', $text);
    }
}
