<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Activity extends Model
{
    protected $table = 'activity';
    protected $primaryKey = "activity_id";

    use HasFactory;

    protected $fillable = [
        'activity_id',
        'athlete_id',
        'name',
        'type',
        'elapsed_time',
        'distance',
        'total_elevation_gain',
        'start_date',
        'start_date_local',
        'utc_offset',
        'kudos_count'
    ];
}