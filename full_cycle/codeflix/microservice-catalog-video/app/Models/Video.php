<?php

namespace App\Models;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function genres()
    {
        return $this->belongsTo(Genre::class);
    }

    public function castMembers()
    {
        return $this->belongsTo(CastMember::class, 'cast_member_video');
    }

    public function media()
    {
        return $this->hasOne(Media::class)
                        ->where('type', MediaTypes::VIDEO->value);
    }

    public function trailer()
    {
        return $this->hasOne(Media::class)
                        ->where('type', MediaTypes::TRAILER->value);
    }

    public function banner()
    {
        return $this->hasOne(ImagesVideo::class)
                        ->where('type', ImageTypes::BANNER->value);
    }

    public function thumb()
    {
        return $this->hasOne(ImagesVideo::class)
                        ->where('type', ImageTypes::THUMB->value);
    }

    public function thumbHalf()
    {
        return $this->hasOne(ImagesVideo::class)
                        ->where('type', ImageTypes::THUMB_HALF->value);
    }
}
