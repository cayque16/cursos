<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\GenreInputDto;
use Core\UseCase\Genre\ListGenreUseCase;
use Tests\TestCase;
use App\Models\Genre as GenreModel;

class ListGenreUseCaseTest extends TestCase
{
    public function testFindById()
    {
        $useCase = new ListGenreUseCase(
            new GenreEloquentRepository(new GenreModel())
        );

        $genre = GenreModel::factory()->create();

        $response = $useCase->execute(new GenreInputDto(id: $genre->id));

        $this->assertEquals($genre->id, $response->id);
        $this->assertEquals($genre->name, $response->name);
    }
}
