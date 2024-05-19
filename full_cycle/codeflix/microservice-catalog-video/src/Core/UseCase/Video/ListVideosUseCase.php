<?php

namespace Core\UseCase\Video;

use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\ListVideos\ListVideosInputDto;

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {
    }

    public function execute(ListVideosInputDto $input): PaginationInterface
    {
        return $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );
    }
}
