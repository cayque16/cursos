<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre as GenreModel;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\GenreInputDto;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Tests\TestCase;

class DeleteGenreUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $useCase = new DeleteGenreUseCase(
            new GenreEloquentRepository(new GenreModel())
        );

        $genre = GenreModel::factory()->create();

        $response = $useCase->execute(new GenreInputDto(id: $genre->id));

        $this->assertTrue($response->success);
    }
}
