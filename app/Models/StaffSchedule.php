<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    protected $fillable = ['user_id', 'day', 'start_time', 'end_time', 'is_off'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
