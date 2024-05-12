<?php

namespace Tests\Unit\App\Models;

use App\Models\ImagesVideo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\UuidTrait;

class ImagesVideoUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new ImagesVideo();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            UuidTrait::class
        ];
    }
    protected function fillable(): array
    {
        return [
            'path',
            'type',
        ];

    }
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime'
        ];
    }
}
