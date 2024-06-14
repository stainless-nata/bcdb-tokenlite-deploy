<?php
/**
 * Activity Model
 *
 * Store the activity of user
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Models;

use App\BigChainDB\BigChainModel;

class Activity extends BigChainModel
{
    /*
     * Table Name Specified
     */
    protected static $table = 'activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'device', 'browser', 'ip',
    ];
}
