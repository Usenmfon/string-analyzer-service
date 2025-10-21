<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StringEntry extends Model
{
    protected $table = 'strings';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'value',
        'length',
        'is_palindrome',
        'unique_characters',
        'word_count',
        'properties',
    ];

    protected $casts = [
        'is_palindrome' => 'boolean',
        'properties' => 'array',
        'length' => 'integer',
        'unique_characters' => 'integer',
        'word_count' => 'integer',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
