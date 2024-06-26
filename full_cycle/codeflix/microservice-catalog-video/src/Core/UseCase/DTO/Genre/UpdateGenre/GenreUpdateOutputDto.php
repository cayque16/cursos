<?php

namespace Core\UseCase\DTO\Genre\UpdateGenre;

class GenreUpdateOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active,
        public array $categories,
        public string $created_at = '',
    ) {
    }
}
