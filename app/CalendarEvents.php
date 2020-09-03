<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalendarEvents extends Model
{
    protected $fillable = [
        'date', 'event_name'
    ];
}
