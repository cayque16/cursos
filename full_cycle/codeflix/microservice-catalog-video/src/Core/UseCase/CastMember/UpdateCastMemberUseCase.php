<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\UpdateCastMembers\UpdateCastMemberInputDto;
use Core\UseCase\DTO\CastMember\UpdateCastMembers\UpdateCastMemberOutputDto;

class UpdateCastMemberUseCase
{
    public function __construct(
        protected CastMemberRepositoryInterface $repository,
    ) { }

    public function execute(UpdateCastMemberInputDto $input): UpdateCastMemberOutputDto
    {
        $entity = $this->repository->findById($input->id);
        $entity->update($input->name);

        $this->repository->update($entity);

        return new UpdateCastMemberOutputDto(
            id: $entity->id(),
            name: $entity->name,
            type: $entity->type->value,
            created_at: $entity->createdAt()
        );
    }
}
