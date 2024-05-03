<?php

namespace Core\UseCase\DTO\Genre\CreateGenre;

class GenreCreateInputDto
{
    public function __construct(
        public string $name,
        public array $categories = [],
        public bool $isActive = true,
    ) { }
}
