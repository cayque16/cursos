<?php

namespace Core\UseCase\DTO\Video\CreateVideo;

use Core\Domain\Enum\Rating;

class CreateVideoInputDto
{
    public function __construct(
        public string $title,
        public string $description,
        public int $yearLaunched,
        public int $duration,
        public bool $opened,
        public Rating $rating,
    ) { }
}
