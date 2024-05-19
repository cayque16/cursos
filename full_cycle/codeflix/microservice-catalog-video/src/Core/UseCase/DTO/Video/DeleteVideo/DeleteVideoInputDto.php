<?php

namespace Core\UseCase\DTO\Video\DeleteVideo;

class DeleteVideoInputDto
{
    public function __construct(
        public string $id
    ) {
    }
}
