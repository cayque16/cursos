<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\ListVideos\ListVideosInputDto;
use Core\UseCase\Video\ListVideosUseCase;
use Tests\TestCase;

class ListVideosUseCaseTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testPagination(
        int $total,
        int $perPage
    ) {
        Video::factory()->count($total)->create();

        $useCase = new ListVideosUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $response = $useCase->execute(new ListVideosInputDto(
            filter: '',
            order: 'desc',
            page: 1,
            totalPage: $perPage,
        ));

        $this->assertCount($perPage, $response->items);
        $this->assertEquals($total, $response->total);
    }

    protected function provider(): array
    {
        return [
            '#data 1' => [
                'total' => 30,
                'perPage' => 10,
            ],
            '#data 2' => [
                'total' => 20,
                'perPage' => 5,
            ],
        ];
    }
}
