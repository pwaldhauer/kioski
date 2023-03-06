<?php

namespace App\Models;

use App\Enums\Options;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $primaryKey = 'key';

    protected $fillable = ['key', 'value'];

    public static function for(Options $option): Option
    {
        return Option::query()->where('key', $option->value)->first() ?? Option::make(['key' => $option->value, 'value' => '']);
    }
}
