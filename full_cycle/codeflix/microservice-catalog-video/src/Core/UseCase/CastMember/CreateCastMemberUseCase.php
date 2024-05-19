<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\CreateCastMember\CreateCastMemberInputDto;
use Core\UseCase\DTO\CastMember\CreateCastMember\CreateCastMemberOutputDto;

class CreateCastMemberUseCase
{
    public function __construct(
        protected CastMemberRepositoryInterface $repository,
    ) {
    }

    public function execute(CreateCastMemberInputDto $input): CreateCastMemberOutputDto
    {
        $entity = new CastMember(
            name: $input->name,
            // type: $input->type == 1 ? CastMemberType::DIRECTOR : CastMemberType::ACTOR
            type: CastMemberType::from($input->type)
        );

        $this->repository->insert($entity);

        return new CreateCastMemberOutputDto(
            id: $entity->id(),
            name: $entity->name,
            type: $input->type,
            created_at: $entity->createdAt()
        );
    }
}
