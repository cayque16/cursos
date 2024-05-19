<?php

namespace Core\UseCase\DTO\Video\ChangeEncoded;

class ChangeEncodedOutputDto
{
    public function __construct(
        public string $id,
        public string $encodedPath,
    ) {
    }
}
