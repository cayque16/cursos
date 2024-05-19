<?php

namespace Core\UseCase\DTO\CastMember\UpdateCastMembers;

class UpdateCastMemberOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public string $created_at = ''
    ) {
    }
}
