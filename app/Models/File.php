<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "link",
        "post_id",
        "type",
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($object) {
            $object->user_id = user()->id;
        });
    }
}
