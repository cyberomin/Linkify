<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Links extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'links';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['code', 'url'];

    /**
     * The attributes that are guarded
     *
     * @var array
     */
	protected $guarded = ['id'];
}
