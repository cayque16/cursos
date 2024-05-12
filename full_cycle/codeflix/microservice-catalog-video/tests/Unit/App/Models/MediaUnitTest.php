<?php

namespace Tests\Unit\App\Models;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\UuidTrait;

class MediaUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Media();
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
            'file_path',
            'encode_path',
            'media_status',
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
