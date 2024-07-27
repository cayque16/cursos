<?php

use Core\Category\Application\DTO\InputCategoryDTO;
use Core\Category\Application\DTO\OutputCategoryDTO;
use Core\Category\Application\UseCase\GetCategoryUseCase;
use Core\Category\Domain\Entities\Category;
use Core\Category\Domain\Repository\CategoryRepositoryInterface;
use Core\SeedWork\Domain\ValueObjects\Uuid;
use Mockery;

afterAll( fn () => Mockery::close());

test('unit test get category', function () {
    $uuid = new Uuid(Uuid::random());
    $mockCategory = Mockery::mock(Category::class, [
        'name', $uuid, 'desc', true
    ]);
    $mockCategory->shouldReceive('id')->andReturn((string) $uuid);
    $mockCategory->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

    $inputDto = new InputCategoryDTO(id: '1231');

    $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
    $mockRepository->shouldReceive('findOne')
        ->once()
        ->with($inputDto->id)
        ->andReturn($mockCategory);

    $useCase = new GetCategoryUseCase($mockRepository);
    $response = $useCase->execute(input: $inputDto);

    expect($response)->toBeInstanceOf(OutputCategoryDTO::class);
});
