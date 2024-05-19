<?php

namespace Core\UseCase\DTO\Video\DeleteVideo;

class DeleteVideoOutputDto
{
    public function __construct(
        public bool $success
    ) {
    }
}
