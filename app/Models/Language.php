<?php

namespace App\Models;
use App\BigChainDB\BigChainModel;

class Language extends BigChainModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
   protected static $table = 'languages';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'label', 'short', 'code'];

	/**
     *
     * Relation with user
     *
     * @version 1.0.1
     * @since 1.0
     * @return void
     */
    public function translate()
    {
        return $this->hasMany(Translate::class, 'name', 'code');
    }
}
