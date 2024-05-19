<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\DTO\CastMember\CastMemberInputDto;
use Core\UseCase\DTO\CastMember\DeleteCastMember\DeleteCastMemberOutputDto;

class DeleteCastMemberUseCase
{
    public function __construct(
        protected CastMemberRepositoryInterface $repository,
    ) {
    }

    public function execute(CastMemberInputDto $input): DeleteCastMemberOutputDto
    {
        $hasDeleted = $this->repository->delete($input->id);

        return new DeleteCastMemberOutputDto($hasDeleted);
    }
}
