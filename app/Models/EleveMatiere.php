<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EleveMatiere
 * 
 * @property int $user_id
 * @property int $matiere_id
 * 
 * @property User $user
 * @property Matiere $matiere
 *
 * @package App\Models
 */
class EleveMatiere extends Model
{
	protected $table = 'eleve_matiere';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'matiere_id' => 'int'
	];

	protected $fillable = [
		'user_id', 'matiere_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}
}
