<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Room
 *
 * @mixin \Eloquent
 */
class Room extends Model  {


	public $table = "rooms";
	
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
    	"name"
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [];

	/**
	 * Атрибуты, исключенные из JSON-представления модели.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = true;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(User::class);
	}
}