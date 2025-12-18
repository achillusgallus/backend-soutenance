<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Matiere
 * 
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property int $created_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property User $user
 * @property Collection|Cour[] $cours
 * @property Collection|EleveMatiere[] $eleve_matieres
 * @property Collection|Forum[] $forums
 * @property Collection|ProfesseurMatiere[] $professeur_matieres
 * @property Collection|Quiz[] $quizzes
 *
 * @package App\Models
 */
class Matiere extends Model
{
	protected $table = 'matieres';

	protected $casts = [
		'created_by' => 'int'
	];

	protected $fillable = [
		'nom',
		'description',
		'user_id'
	];

    public function createur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function professeurs()
    {
        return $this->belongsToMany(User::class, 'professeur_matiere');
    }

    public function eleves()
    {
        return $this->belongsToMany(User::class, 'eleve_matiere');
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }

    public function forums()
    {
        return $this->hasMany(Forum::class);
    }
}
