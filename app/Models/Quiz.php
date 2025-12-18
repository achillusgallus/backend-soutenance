<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Quiz
 * 
 * @property int $id
 * @property int $matiere_id
 * @property int $professeur_id
 * @property string $titre
 * @property int $duree
 * @property Carbon $created_at
 * 
 * @property Matiere $matiere
 * @property User $user
 * @property Collection|Question[] $questions
 * @property Collection|ResultatsQuiz[] $resultats_quizzes
 *
 * @package App\Models
 */
class Quiz extends Model
{
	protected $table = 'quiz';
	public $timestamps = false;

	protected $casts = [
		'matiere_id' => 'int',
		'professeur_id' => 'int',
		'duree' => 'int'
	];

	protected $fillable = [
		'matiere_id',
		'professeur_id',
		'titre',
		'duree'
	];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function resultats()
    {
        return $this->hasMany(ResultatsQuiz::class);
    }
}
