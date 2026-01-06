<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lastname',
        'firstname',
        'middlename',
        'birthday',
        'address',
        'contact_number',
        'college',
        'course',
        'email',
        'medical_status', // Ensure these new fields are here
        'date_checked'
    ];

    // --- THIS WAS MISSING ---
    // This tells Laravel: "This profile belongs to a specific User"
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper to get formatted full name: "Lacdao, Nicolas Andrei Castro"
    public function getFormattedNameAttribute()
    {
        $middle = ($this->middlename && $this->middlename !== 'N/A') ? ' ' . $this->middlename : '';
        return strtoupper("{$this->lastname}, {$this->firstname}{$middle}");
    }
}
