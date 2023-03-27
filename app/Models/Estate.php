<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Estate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'supervisor_user_id',
        'street',
        'building_number',
        'city',
        'zip'
    ];

    public function supervisorUser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_user_id', 'user_id');
    }
}
