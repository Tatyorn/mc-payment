<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
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

    public function render(): JsonResponse
    {
        return response()->json([
            'type' => $this->type,
            'title' => $this->title,
            'status' => $this->status,
            'detail' => $this->message,
        ], $this->status);
    }
}
