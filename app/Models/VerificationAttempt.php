<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationAttempt extends Model
{
    protected $table = 'verification_attempts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'otp',
        'success'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
