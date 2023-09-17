<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiLog extends Model
{
    use HasFactory, softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bear_id',
        'action',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function bear() : BelongsTo {
        return $this->belongsTo(Bear::class);
    }
}
