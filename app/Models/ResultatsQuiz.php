<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ResultatsQuiz
 * 
 * @property int $id
 * @property int $quiz_id
 * @property int $eleve_id
 * @property float $score
 * @property Carbon $date_passage
 * 
 * @property Quiz $quiz
 * @property User $user
 *
 * @package App\Models
 */
class ResultatsQuiz extends Model
{
	protected $table = 'resultats_quiz';
	public $timestamps = false;

	protected $casts = [
		'quiz_id' => 'int',
		'eleve_id' => 'int',
		'score' => 'float',
		'date_passage' => 'datetime'
	];

	protected $fillable = [
		'quiz_id',
		'eleve_id',
		'score',
		'date_passage'
	];

	public function quiz()
	{
		return $this->belongsTo(Quiz::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'eleve_id');
	}
}
