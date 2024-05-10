<?php

namespace Core\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\ListVideos\ListVideosInputDto;
use Core\UseCase\DTO\Video\ListVideos\ListVideosOutputDto;

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) { }

    public function execute(ListVideosInputDto $input): ListVideosOutputDto
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );

        return new ListVideosOutputDto(
            items: $response->items(),
            total: $response->total(),
            current_page: $response->currentPage(),
            last_page: $response->lastPage(),
            first_page: $response->firstPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from(),
        );
    }
}
