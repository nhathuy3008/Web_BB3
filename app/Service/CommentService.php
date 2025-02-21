<?php

namespace App\Service;

use App\Model\Comment;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class CommentService
{
    protected $client;
    protected $huggingFaceApiUrl;
    protected $huggingFaceApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->huggingFaceApiUrl = env('HUGGING_FACE_API_URL');
        $this->huggingFaceApiKey = env('HUGGING_FACE_API_KEY');
    }



    public function createComment($productId, $accountId, $content)
    {
        // Kiểm tra bình luận có độc hại không
        if ($this->isToxicComment($content)) {
            throw new \Exception('Bình luận của bạn đã bị chặn do chứa nội dung độc hại. Vui lòng sửa đổi và thử lại.');
        }
    
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

    protected function isToxicComment($content)
{
    try {
        $response = $this->client->post($this->huggingFaceApiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->huggingFaceApiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'inputs' => $content,
            ],
        ]);

        $body = json_decode($response->getBody(), true);

        // Ghi log toàn bộ phản hồi từ API
        Log::info('API Response:', $body);

        // Kiểm tra phản hồi từ Hugging Face API
        if (isset($body[0][0]['score'])) {
            $toxicScore = $body[0][0]['score'];
            Log::info('Toxicity score:', ['score' => $toxicScore]);

            // Chỉ chặn bình luận nếu điểm số vượt quá ngưỡng
            if ($toxicScore > 0.35) {
                Log::warning('Comment is toxic and will be blocked.');
                return true; // Chặn bình luận nếu điểm số vượt ngưỡng
            }
        }

        Log::info('Comment is not toxic.');
        return false; // Trả về false nếu không có score trong phản hồi

    } catch (\Exception $e) {
        Log::error('Error checking toxicity:', ['message' => $e->getMessage()]);
        return false; // Nếu có lỗi xảy ra, cho phép bình luận
    }
}
}