<?php

namespace App\Models;

use App\BigChainDB\BigChainModel;

class Translate extends BigChainModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static $table = 'translates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'name', 'text', 'pages', 'group', 'panel', 'load'];
}
