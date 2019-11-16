<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Comment::paginate(10), 200);
    }

    /**
     * @param CreateCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateCommentRequest $request, $postTitle)
    {
        $post = Post::where('title', $this->slug($postTitle))->first();

        if (empty($post)) {
            return response()->json(['message' => 'No result found'], 404);
        }

        return response()->json(Comment::create([
            'body' => $request->get('body'),
            'commentable_type' => 'App\\Post',
            'commentable_id' => $post->id,
            'creator_id' => Auth::user()->id,
        ]), 201);
    }

    /**
     * @param Request $request
     * @param         $title
     * @return \Illuminate\Http\JsonResponse
     */
    public function patch(CreateCommentRequest $request, $postTitle, $commentId)
    {
        if (!Post::where('title', $this->slug($postTitle))->exists()) {
            return response()->json(['message' => 'No result found'], 404);
        }

        $coment = Comment::find($commentId);
        $coment->body = $request->get('body');
        $coment->save();

        return response()->json($coment, 200);
    }

    /**
     * @param $title
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($postTitle, $id)
    {
        if (!Post::where('title', $this->slug($postTitle))->exists()) {
            return response()->json(['message' => 'No result found'], 404);
        }

        if (Comment::find($id)->delete()) {
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
