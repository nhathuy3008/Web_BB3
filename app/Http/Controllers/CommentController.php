<?php

namespace App\Http\Controllers;

use App\Model\Comment;
use App\Service\CommentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function addComment(Request $request)
{
    \Log::info('Received request:', $request->all());

    // Validate the request
    try {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'account_id' => 'required|uuid|exists:accounts,id',
            'content' => 'required|string|max:200',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed:', $e->errors());
        return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Create the comment
    $comment = $this->commentService->createComment(
        $request->product_id,
        $request->account_id,
        $request->content
    );

    \Log::info('Comment created:', $comment->toArray());

    return response()->json($comment, Response::HTTP_CREATED);
}

    public function getCommentsByProduct($productId)
    {
        $comments = $this->commentService->getCommentsByProduct($productId);

        return response()->json($comments, Response::HTTP_OK);
    }
}