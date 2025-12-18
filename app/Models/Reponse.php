<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reponse
 * 
 * @property int $id
 * @property int $question_id
 * @property string $reponse
 * @property bool|null $est_correcte
 * 
 * @property Question $question
 *
 * @package App\Models
 */
class Reponse extends Model
{
	protected $table = 'reponses';
	public $timestamps = false;

	protected $casts = [
		'question_id' => 'int',
		'est_correcte' => 'bool'
	];

	protected $fillable = [
		'question_id',
		'reponse',
		'est_correcte'
	];

	public function question()
	{
		return $this->belongsTo(Question::class);
	}
}
