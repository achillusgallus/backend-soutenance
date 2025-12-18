<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EleveMatiere
 * 
 * @property int $eleve_id
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
		'eleve_id' => 'int',
		'matiere_id' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'eleve_id');
	}

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}
}
