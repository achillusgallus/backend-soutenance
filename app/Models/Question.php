<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * 
 * @property int $id
 * @property int $quiz_id
 * @property string $question
 * @property string $type
 * 
 * @property Quiz $quiz
 * @property Collection|Reponse[] $reponses
 *
 * @package App\Models
 */
class Question extends Model
{
	protected $table = 'questions';
	public $timestamps = false;

	protected $casts = [
		'quiz_id' => 'int'
	];

	protected $fillable = [
		'quiz_id',
		'question',
		'type'
	];

	public function quiz()
	{
		return $this->belongsTo(Quiz::class);
	}
		public function reponses()
	{
		return $this->hasMany(Reponse::class, 'question_id');
	}
}
