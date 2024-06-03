<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['event_name_id', 'day', 'time'];

    public function eventName()
    {
        return $this->belongsTo(EventName::class, 'event_name_id');
    }
    
}
