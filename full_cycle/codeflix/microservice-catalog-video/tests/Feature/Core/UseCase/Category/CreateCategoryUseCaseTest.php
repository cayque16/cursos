<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CreateCategory\CreateCategoryInputDto;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $repository = new CategoryEloquentRepository(new CategoryModel());
        $useCase = new CreateCategoryUseCase($repository);
        $response = $useCase->execute(
            new CreateCategoryInputDto(
                name: 'Test'
            )
        );

        $this->assertEquals('Test', $response->name);
        $this->assertNotEmpty($response->id);
        $this->assertDatabaseHas('categories', ['id' => $response->id]);
    }
}
