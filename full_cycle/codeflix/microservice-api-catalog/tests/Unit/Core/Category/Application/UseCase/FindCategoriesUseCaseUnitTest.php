<?php

use Core\Category\Application\DTO\InputCategoriesDTO;
use Core\Category\Application\DTO\OutputCategoriesDTO;
use Core\Category\Application\DTO\OutputCategoryDTO;
use Core\Category\Application\UseCase\FindCategoriesUseCase;
use Core\Category\Domain\Entities\Category;
use Core\Category\Domain\Repository\CategoryRepositoryInterface;

test('unit test get categories', function () {
    $inputDto = new InputCategoriesDTO(filter: 'abc');

    $mockCategory = [
        new Category(name: 'name 1'),
        new Category(name: 'name 2'),
    ];

    $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
    $mockRepository->shouldReceive('findAll')
        ->once()
        ->with($inputDto->filter)
        ->andReturn($mockCategory);


    $useCase = new FindCategoriesUseCase(repository: $mockRepository);

    $response = $useCase->execute(input: $inputDto);

    expect($response)->toBeInstanceOf(OutputCategoriesDTO::class);
    expect($response->total)->toBe(count($mockCategory));
    array_map(fn ($item) => expect($item)->toBeInstanceOf(OutputCategoryDTO::class), $response->items);
});
