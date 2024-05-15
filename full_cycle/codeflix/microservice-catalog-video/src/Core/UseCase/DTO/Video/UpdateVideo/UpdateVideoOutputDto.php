<?php

namespace Core\UseCase\DTO\Video\UpdateVideo;

use Core\Domain\Enum\Rating;

class UpdateVideoOutputDto
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int $yearLaunched,
        public int $duration,
        public bool $opened,
        public Rating $rating,
        public string $createdAt,
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
