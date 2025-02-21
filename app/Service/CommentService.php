<?php
namespace App\Service;

use App\Model\Comment;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function createComment($productId, $accountId, $content)
    {
        Log::info('Creating comment with:', [
            'product_id' => $productId,
            'account_id' => $accountId,
            'content' => $content,
        ]);

        $comment = Comment::create([
            'product_id' => $productId,
            'account_id' => $accountId,
            'content' => $content,
        ]);

        Log::info('Comment created:', $comment->toArray());

        return $comment;
    }

    public function getCommentsByProduct($productId)
    {
        return Comment::where('product_id', $productId)->with('account')->get();
    }
}