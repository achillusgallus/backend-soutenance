<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Cour[] $cours
 * @property Collection|EleveMatiere[] $eleve_matieres
 * @property Collection|Forum[] $forums
 * @property Collection|Matiere[] $matieres
 * @property Collection|MessagesForum[] $messages_forums
 * @property Collection|ProfesseurMatiere[] $professeur_matieres
 * @property Collection|Quiz[] $quizzes
 * @property Collection|ResultatsQuiz[] $resultats_quizzes
 * @property Collection|Role[] $roles
 * @property Collection|SujetsForum[] $sujets_forums
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
        'role_id'
	];

    /* ===== ROLES ===== */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }

    public function isProfesseur()
    {
        return $this->roles()->where('name', 'professeur')->exists();
    }

    public function isEleve()
    {
        return $this->roles()->where('name', 'eleve')->exists();
    }

    /* ===== MATIERES ===== */
    public function matieresProfesseur()
    {
        return $this->belongsToMany(Matiere::class, 'professeur_matiere');
    }

    public function matieresEleve()
    {
        return $this->belongsToMany(Matiere::class, 'eleve_matiere');
    }

    /* ===== COURS ===== */
    public function cours()
    {
        return $this->hasMany(Cours::class, 'professeur_id');
    }

    /* ===== QUIZ ===== */
    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'professeur_id');
    }

    public function resultatsQuiz()
    {
        return $this->hasMany(ResultatsQuiz::class, 'eleve_id');
    }

    /* ===== FORUM ===== */
    public function sujetsForum()
    {
        return $this->hasMany(SujetsForum::class);
    }

    public function messagesForum()
    {
        return $this->hasMany(MessagesForum::class);
    }
}
