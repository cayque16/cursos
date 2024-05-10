<?php

namespace Core\UseCase\DTO\Video\ListVideos;

class ListVideosInputDto
{
    public function __construct(
        public string $filter = '',
        public string $order = 'DESC',
        public int $page = 1,
        public int $totalPage = 15
    ) {}
}
