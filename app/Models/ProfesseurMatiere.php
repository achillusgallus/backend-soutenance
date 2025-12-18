<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfesseurMatiere
 * 
 * @property int $professeur_id
 * @property int $matiere_id
 * 
 * @property User $user
 * @property Matiere $matiere
 *
 * @package App\Models
 */
class ProfesseurMatiere extends Model
{
	protected $table = 'professeur_matiere';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'professeur_id' => 'int',
		'matiere_id' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'professeur_id');
	}

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}
}
