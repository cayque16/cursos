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
        $categories = $this->model->where(function ($query) use ($filter){
            $query->where('name', 'LIKE', "%{$filter}%");
        })->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
    }

    public function update(EntityCategory $category): EntityCategory
    {
        if (!$categoryDb = $this->model->find($category->id())) {
            throw new NotFoundException();
        }

        $categoryDb->update([
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
        ]);

        $categoryDb->refresh();

        return $this->toCategory($categoryDb);
    }

    public function delete(string $id): bool
    {
        if (!$categoryDb = $this->model->find($id)) {
            throw new NotFoundException();
        }

        return $categoryDb->delete();
    }

    public function toCategory(object $data): EntityCategory
    {
        $entity =  new EntityCategory(
            id: $data->id,
            name: $data->name,
            description: $data->description
        );

        ((bool) $entity->isActive) ? $entity->activate() : $entity->disable();

        return $entity;
    }
}
