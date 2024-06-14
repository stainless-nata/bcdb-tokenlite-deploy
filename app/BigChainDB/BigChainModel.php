<?php
/**
 * BigChain Model : Base Class
 *
 */
namespace App\BigChainDB;

use Illuminate\Support\Str;

class BigChainModel
{
    protected static $table;
    public $timestamps = false;
    public $attributes = [];

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array|\stdClass $attributes
     * @return void
     */
    public function __construct($attributes = null) {
        if(!$attributes) return;
        foreach ($attributes as $key => $value) {
            $key = Str::contains($key, '.') ? last(explode('.', $key)) : $key;
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if(!isset($this->attributes[$key])) {
            $this->attributes[$key] = null;
        }
        return $this->attributes[$key];
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function save() {
        
    }

    public static function __callStatic($method, $parameters)
    {
        $query = self::newQuery();
        if(method_exists($query, $method))
            return call_user_func_array([$query, $method], $parameters);
        return (new static)->$method(...$parameters);
    }

    public static function newQuery() {
        return new BigChainQuery(static::$table, static::class);
    }

}
