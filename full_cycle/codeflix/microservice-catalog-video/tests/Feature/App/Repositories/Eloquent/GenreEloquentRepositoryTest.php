<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre as GenreModel;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre as GenreEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Tests\TestCase;

class GenreEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new GenreEloquentRepository(new GenreModel());
    }

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new GenreEntity(name: 'New genre');

        $response = $this->repository->insert($entity);

        $this->assertEquals($entity->id, $response->id);
        $this->assertEquals($entity->name, $response->name);
        $this->assertDatabaseHas('genres', [
            'id' => $entity->id()
        ]);
    }

    public function testInsertDeactivate()
    {
        $entity = new GenreEntity(name: 'New genre');
        $entity->disable();

        $response = $this->repository->insert($entity);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'is_active' => false,
        ]);
    }

    public function testInsertWithRelationships()
    {
        $categories = Category::factory()->count(4)->create();

        $genre = new GenreEntity(name: 'teste');
        foreach ($categories as $category) {
            $genre->addCategory($category->id);
        }

        $response = $this->repository->insert($genre);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id(),
        ]);

        $this->assertDatabaseCount('category_genre', 4);
    }

    public function testNotFoundFindById()
    {
        $this->expectException(NotFoundException::class);

        $genre = 'fake_value';

        $this->repository->findById($genre);
    }

    public function testFindById()
    {
        $genre = GenreModel::factory()->create();

        $response = $this->repository->findById($genre->id);
        $this->assertEquals($genre->id, $response->id());
        $this->assertEquals($genre->name, $response->name);
    }

    public function testFindAll()
    {
        $genres = GenreModel::factory()->count(10)->create();

        $genreDb = $this->repository->findAll();

        $this->assertEquals(count($genres), count($genreDb));
    }

    public function testFindAllEmpty()
    {
        $genreDb = $this->repository->findAll();

        $this->assertCount(0, $genreDb);
    }

    public function testFindAllWithFilter()
    {
        GenreModel::factory()->count(10)->create([
            'name' => 'Teste'
        ]);
        GenreModel::factory()->count(10)->create();

        $genreDb = $this->repository->findAll(
            filter: 'Teste'
        );
        $this->assertEquals(10, count($genreDb));

        $genreDb = $this->repository->findAll();
        $this->assertEquals(20, count($genreDb));
    }

    public function testPagination()
    {
        $genres = GenreModel::factory()->count(60)->create();

        $response = $this->repository->paginate();

        $this->assertEquals(15, count($response->items()));
        $this->assertEquals(60, $response->total());
    }

    public function testPaginationEmpty()
    {
        $response = $this->repository->paginate();

        $this->assertCount(0, $response->items());
        $this->assertEquals(0, $response->total());
    }
}
