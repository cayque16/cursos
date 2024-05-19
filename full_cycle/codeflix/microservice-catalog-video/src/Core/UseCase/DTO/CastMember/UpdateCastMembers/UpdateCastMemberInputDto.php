<?php

namespace Core\UseCase\DTO\CastMember\UpdateCastMembers;

class UpdateCastMemberInputDto
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
