<?php

namespace Core\UseCase\DTO\Category;

class CreateCategoryOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description = '',
        public string $is_active = true,
    ) {}
}
