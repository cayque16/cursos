<?php

namespace Core\UseCase\Genre;

use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\UpdateGenre\GenreUpdateInputDto;
use Core\UseCase\DTO\Genre\UpdateGenre\GenreUpdateOutputDto;
use Core\UseCase\Interfaces\TransactionInterface;
use Throwable;

class UpdateGenreUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function execute(GenreUpdateInputDto $input): GenreUpdateOutputDto
    {
        $genre = $this->repository->findById($input->id);

        try {
            $genre->update(name: $input->name);

            foreach ($input->categories as $categoryId) {
                $genre->addCategory($categoryId);
            }

            $this->validateCategoriesId($input->categories);

            $genreDb = $this->repository->update($genre);

            $this->transaction->commit();

            return new GenreUpdateOutputDto(
                id: (string) $genreDb->id,
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                created_at: $genreDb->createdAt(),
                categories: $genreDb->categories,
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
