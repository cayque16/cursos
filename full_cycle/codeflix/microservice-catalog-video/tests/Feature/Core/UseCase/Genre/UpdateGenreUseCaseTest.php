<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Models\Category as CategoryModel;
use App\Models\Genre as GenreModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Transaction\DbTransaction;
use Core\Domain\Exception\NotFoundException;
use Core\UseCase\DTO\Genre\UpdateGenre\GenreUpdateInputDto;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Tests\TestCase;
use Throwable;

class UpdateGenreUseCaseTest extends TestCase
{
    protected $repositoryGenre;

    protected $repositoryCategory;

    protected function setUp(): void
    {
        $this->repositoryGenre = new GenreEloquentRepository(new GenreModel());
        $this->repositoryCategory = new CategoryEloquentRepository(new CategoryModel());

        parent::setUp();
    }

    public function testUpdate()
    {
        $categories =  CategoryModel::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        $useCase = new UpdateGenreUseCase(
            $this->repositoryGenre,
            new DbTransaction(),
            $this->repositoryCategory
        );

        $genre = GenreModel::factory()->create();

        $useCase->execute(
            new GenreUpdateInputDto(
                id: $genre->id,
                name: 'Teste',
                categories: $categoriesId
            ),
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'Teste'
        ]);
        $this->assertDatabaseCount('category_genre', 10);
    }

    public function testExceptionUpdateGenreWithCategoriesIdsInvalid()
    {
        $this->expectException(NotFoundException::class);

        $categories =  CategoryModel::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();
        array_push($categoriesId, 'fake_id');

        $useCase = new UpdateGenreUseCase(
            $this->repositoryGenre,
            new DbTransaction(),
            $this->repositoryCategory
        );

        $genre = GenreModel::factory()->create();

        $useCase->execute(
            new GenreUpdateInputDto(
                id: $genre->id,
                name: 'Teste',
                categories: $categoriesId
            ),
        );
    }

    public function testTransactionUpdate()
    {
        $categories =  CategoryModel::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        $useCase = new UpdateGenreUseCase(
            $this->repositoryGenre,
            new DbTransaction(),
            $this->repositoryCategory
        );

        $genre = GenreModel::factory()->create();

        try {
            $useCase->execute(
                new GenreUpdateInputDto(
                    id: $genre->id,
                    name: 'Teste',
                    categories: $categoriesId
                ),
            );

            $this->assertDatabaseHas('genres', [
                'name' => 'Teste'
            ]);
            $this->assertDatabaseCount('category_genre', 10);
        } catch (Throwable $th) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }
    }
}
