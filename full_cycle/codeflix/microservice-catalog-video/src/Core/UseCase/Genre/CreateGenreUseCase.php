<?php

namespace Core\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateInputDto;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateOutputDto;
use Core\UseCase\Interfaces\TransactionInterface;
use Throwable;

class CreateGenreUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function execute(GenreCreateInputDto $input): GenreCreateOutputDto
    {
        try {
            $genre = new Genre(
                name: $input->name,
                isActive: $input->isActive,
                categories: $input->categories
            );

            $this->validateCategoriesId($input->categories);

            $genreDb = $this->repository->insert($genre);

            $this->transaction->commit();

            return new GenreCreateOutputDto(
                id: (string) $genreDb->id,
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                created_at: $genreDb->createdAt(),
            );
        } catch (Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    private function validateCategoriesId(array $categories = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categories);

        $arrayDiff = array_diff($categories, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }
}
