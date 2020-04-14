<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'original_id', 'title', 'image', 'body',
    ];

    /**
     * Check that we haven't this article in DB
     * @var $originalId
     *
     * @return bool
     */
    public static function checkByOriginalId(string $originalId): bool
    {
        return Article::where('original_id', $originalId)->count() === 0;
    }
}
