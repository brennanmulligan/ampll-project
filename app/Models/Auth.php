<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Auth extends Model
{
    protected $table = "auth";
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'refresh_token',
        'access_token'
    ];
}
