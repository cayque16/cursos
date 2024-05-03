<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Entity\Genre as GenreEntity;
use App\Models\Genre as GenreModel;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    public function __construct(
        protected GenreModel $model
    ) { }

    public function insert(GenreEntity $genre): GenreEntity
    {
        $genreDb = $this->model->create([
            'id' => $genre->id(),
            'name' => $genre->name,
            'is_active' => $genre->isActive,
            'created_at' => $genre->createdAt(),
        ]);

        if (count($genre->categories) > 0) {
            $genreDb->categories()->sync($genre->categories);
        }

        return $this->toGenre($genreDb);
    }

    public function findById(string $id): GenreEntity
    {
        if (!$genreDb = $this->model->find($id)) {
            throw new NotFoundException("Genre {$id} not found");
        }

        return $this->toGenre($genreDb);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface
    {

    }

    public function update(GenreEntity $genre): GenreEntity
    {

    }

    public function delete(string $id): bool
    {

    }
    public function toGenre(object $data): GenreEntity
    {
        $entity = new GenreEntity(
            id: new Uuid($data->id),
            name: $data->name,
            createdAt: new DateTime($data->created_at),
        );

        ((bool) $data->is_active) ? $entity->activate() : $entity->disable();

        return $entity;
    }
}
