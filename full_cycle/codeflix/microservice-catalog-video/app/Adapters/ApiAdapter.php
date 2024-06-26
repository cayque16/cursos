<?php

namespace App\Adapters;

use App\Http\Resources\DefaultResource;
use Core\Domain\Repository\PaginationInterface;
use Illuminate\Http\Response;

class ApiAdapter
{
    public function __construct(
        private PaginationInterface $response
    ) {
    }

    public function toJson()
    {
        return DefaultResource::collection($this->response->items())
            ->additional([
                'meta' => [
                    'total' => $this->response->total(),
                    'current_page' => $this->response->currentPage(),
                    'last_page' => $this->response->lastPage(),
                    'first_page' => $this->response->firstPage(),
                    'per_page' => $this->response->perPage(),
                    'to' => $this->response->to(),
                    'from' => $this->response->from(),
                ],
            ]);
    }

    public static function json(object $data, $statusCode = Response::HTTP_OK)
    {
        return (new DefaultResource($data))
            ->response()
            ->setStatusCode($statusCode);
    }
}
