<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\DeleteGenre\DeleteGenreOutputDto;
use Core\UseCase\DTO\Genre\GenreInputDto;

class DeleteGenreUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $repository
    ) {
    }

    public function execute(GenreInputDto $input): DeleteGenreOutputDto
    {
        $success = $this->repository->delete($input->id);

        return new DeleteGenreOutputDto(success: $success);
    }
}
