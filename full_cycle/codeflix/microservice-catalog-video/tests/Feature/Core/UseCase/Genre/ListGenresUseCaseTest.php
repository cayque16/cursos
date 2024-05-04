<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre as GenreModel;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\ListGenres\ListGenresInputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Tests\TestCase;

class ListGenresUseCaseTest extends TestCase
{
    public function testFindAll()
    {
        $useCase = new ListGenresUseCase(
            new GenreEloquentRepository(new GenreModel())
        );

        GenreModel::factory()->count(100)->create();

        $response = $useCase->execute(new ListGenresInputDto());

        $this->assertCount(15, $response->items);
        $this->assertEquals(100, $response->total);
    }
}
