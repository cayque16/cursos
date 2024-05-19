<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategories\ListCategoriesInputDto;
use Tests\TestCase;

class ListCategoriesUseCaseTest extends TestCase
{
    public function testListEmpty()
    {
        $response = $this->createUseCase();

        $this->assertCount(0, $response->items);
    }

    public function testListAll()
    {
        $categories = CategoryModel::factory()->count(20)->create();
        $response = $this->createUseCase();

        $this->assertCount(15, $response->items);
        $this->assertEquals(count($categories), $response->total);
    }

    private function createUseCase()
    {
        $repository = new CategoryEloquentRepository(new CategoryModel());
        $useCase = new ListCategoriesUseCase($repository);

        return $useCase->execute(new ListCategoriesInputDto());
    }
}
