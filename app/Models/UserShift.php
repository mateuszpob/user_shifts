<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserShift extends Model
{
    use HasFactory;

    protected $table = 'users_shifts';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'substitute_user_id',
        'temp_changes',
        'date_from',
        'date_to'
    ];

    protected $casts = [
        'temp_changes' => 'array',
        'date_from' => 'date',
        'date_to' => 'date'
    ];

    public function getEstatesAttribute() : ?Collection
    {
        return Estate::whereIn('id', $this->temp_changes)->get();
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function substituteUser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'substitute_user_id', 'user_id');
    }
}
