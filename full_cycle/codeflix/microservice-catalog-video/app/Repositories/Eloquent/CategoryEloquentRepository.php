<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as ModelCategory;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Illuminate\Database\Eloquent\Model;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    public function __construct(
        protected Model $model
    ) { }

    public function insert(EntityCategory $category): EntityCategory
    {
        $category = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
            'created_at' => $category->createdAt(),
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $id): EntityCategory
    {
        if (!$category = $this->model->find($id)) {
            throw new NotFoundException();
        }

        return $this->toCategory($category);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        return [];
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface
    {
        return new PaginationPresenter();
    }

    public function update(EntityCategory $category): EntityCategory
    {
        return new EntityCategory(
            name: 'test',
        );
    }

    public function delete(string $id): bool
    {
        return true;
    }

    public function toCategory(object $data): EntityCategory
    {
        return new EntityCategory(
            id: $data->id,
            name: $data->name,
        );
    }
}
