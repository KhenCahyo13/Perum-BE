<?php

namespace Modules\Core\Transformers;

use Illuminate\Pagination\LengthAwarePaginator;

class ApiPaginatedResource
{
    public function __construct(
        private readonly LengthAwarePaginator $paginator,
        private readonly string $resourceClass,
    ) {}

    public static function make(LengthAwarePaginator $paginator, string $resourceClass): self
    {
        return new self($paginator, $resourceClass);
    }

    public function data(): mixed
    {
        return ($this->resourceClass)::collection($this->paginator->getCollection());
    }

    public function meta(): array
    {
        return [
            'currentPage' => $this->paginator->currentPage(),
            'lastPage' => $this->paginator->lastPage(),
            'perPage' => $this->paginator->perPage(),
            'total' => $this->paginator->total(),
            'from' => $this->paginator->firstItem(),
            'to' => $this->paginator->lastItem(),
        ];
    }
}
