<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $category = CategoryModel::factory()->create();

        $repository = new CategoryEloquentRepository(new CategoryModel());
        $useCase = new DeleteCategoryUseCase($repository);

        $useCase->execute(
            new CategoryInputDto(id: $category->id)
        );

        $this->assertSoftDeleted($category);
    }
}
