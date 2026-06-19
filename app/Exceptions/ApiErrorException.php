<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class ApiErrorException extends RuntimeException
{
    public function __construct(
        public string $type,
        public string $title,
        public int $status = 422,
        ?string $detail = null,
    ) {
        parent::__construct($detail ?? $title);
    }

    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'type' => $this->type,
                'title' => $this->title,
                'status' => $this->status,
                'detail' => $this->message,
            ], $this->status);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->message);
    }
}
