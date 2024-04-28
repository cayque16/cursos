<?php

namespace Core\UseCase\DTO\Category;

class CreateCategoryInputDto
{
    public function __construct(
        public string $name,
        public string $description = '',
        public string $isActive = true,
    ) {}
}
