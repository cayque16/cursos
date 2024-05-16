<?php

namespace Core\UseCase\DTO\Video\ChangeEncoded;

class ChangeEncodedInputDto
{
    public function __construct(
        public string $id,
        public string $encodedPath,
    ) {}
}
