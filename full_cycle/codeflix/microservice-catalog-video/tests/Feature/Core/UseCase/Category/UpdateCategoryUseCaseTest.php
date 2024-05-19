<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\UpdateCategory\CategoryUpdateInputDto;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function testUpdate()
    {
        $category = CategoryModel::factory()->create();

        $repository = new CategoryEloquentRepository(new CategoryModel());
        $useCase = new UpdateCategoryUseCase($repository);

        $response = $useCase->execute(
            new CategoryUpdateInputDto(
                id: $category->id,
                name: 'name updated'
            )
        );

        $this->assertEquals('name updated', $response->name);
        $this->assertEquals($category->description, $response->description);
        $this->assertDatabaseHas('categories', [
            'name' => $response->name,
        ]);
    }
}
