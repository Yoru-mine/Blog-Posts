<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = [
        'user_id',
        'reaction',
        'reactable_type',
        'reactable_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function reactable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
