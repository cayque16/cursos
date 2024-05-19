<?php

namespace Core\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\DeleteVideo\DeleteVideoInputDto;
use Core\UseCase\DTO\Video\DeleteVideo\DeleteVideoOutputDto;

class DeleteVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository,
    ) {
    }

    public function execute(DeleteVideoInputDto $input): DeleteVideoOutputDto
    {
        $result = $this->repository->delete($input->id);

        return new DeleteVideoOutputDto($result);
    }
}
