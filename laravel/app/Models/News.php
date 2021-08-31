<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'header',
        'overview',
        'text',
        'original_uri',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
