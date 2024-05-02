<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class CategoryEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = new CategoryEloquentRepository(new CategoryModel());
    }

    public function testInsert()
    {
        $entity = new CategoryEntity(
            name: 'Teste',
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name,
        ]);
    }

    public function testFindById()
    {
        $category = CategoryModel::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(CategoryEntity::class, $response);
        $this->assertEquals($category->id, $response->id);
    }

    public function testFindByIdNotFound()
    {
        try {
            $this->repository->findById('fakeValue');
            
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(NotFoundException::class, $th);
        }
    }
}
