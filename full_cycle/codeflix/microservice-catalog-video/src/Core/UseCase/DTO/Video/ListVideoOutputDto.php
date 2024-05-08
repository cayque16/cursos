<?php

namespace Core\UseCase\DTO\Video;

use Core\Domain\Enum\Rating;

class ListVideoOutputDto
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int $yearLaunched,
        public int $duration,
        public bool $opened,
        public Rating $rating,
        public array $categories,
        public array $genres,
        public array $castMembers,
        public ?string $videoFile = null,
        public ?string $thumbFile = null,
        public ?string $thumbHalfFile = null,
        public ?string $bannerFile = null,
        public ?string $trailerFile = null,
    ) { }
}
