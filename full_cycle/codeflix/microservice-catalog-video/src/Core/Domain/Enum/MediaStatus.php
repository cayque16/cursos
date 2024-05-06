<?php

namespace Core\Domain\Enum;

enum MediaStatus: int
{
    case PROCESSING = 1;

    case COMPLETE = 2;

    case PENDING = 3;
}
