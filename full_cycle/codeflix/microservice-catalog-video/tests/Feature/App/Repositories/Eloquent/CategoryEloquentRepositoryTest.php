<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryEloquentRepositoryTest extends TestCase
{
    public function testInsert()
    {
        $repository = new CategoryEloquentRepository(new CategoryModel());

        $entity = new CategoryEntity(
            name: 'Teste',
        );

        $response = $repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name,
        ]);
    }
}
