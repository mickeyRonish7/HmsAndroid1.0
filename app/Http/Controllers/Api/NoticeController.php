<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    private function noticeResource(Notice $notice): array
    {
        return [
            'id'           => $notice->id,
            'title'        => $notice->title,
            'description'  => $notice->content,
            'created_date' => $notice->created_at?->toDateTimeString(),
            'posted_by'    => $notice->postedBy?->name ?? 'Administrator',
        ];
    }

    public function index(Request $request): JsonResponse
    {
        $notices = Notice::with('postedBy')->latest()->get();

        return response()->json([
            'status'  => 'success',
            'total'   => $notices->count(),
            'notices' => $notices->map(fn ($n) => $this->noticeResource($n)),
        ]);
    }
}
