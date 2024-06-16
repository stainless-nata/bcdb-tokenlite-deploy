<?php

namespace App\Models;

use App\BigChainDB\BigChainModel;

class Referral extends BigChainModel
{
    /*
     * Table Name Specified
     */
    protected static $table = 'referrals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_bonus', 'refer_by', 'refer_bonus', 'meta_data'
    ];
}
