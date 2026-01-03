<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class Cour
 * 
 * @property int $id
 * @property int $matiere_id
 * @property int $professeur_id
 * @property string $titre
 * @property string $contenu
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Matiere $matiere
 * @property User $user
 *
 * @package App\Models
 */
class Cours extends Model
{
	protected $table = 'cours';

	protected $casts = [
		'matiere_id' => 'int',
		'professeur_id' => 'int'
	];

	protected $fillable = [
		'matiere_id',
		'professeur_id',
		'titre',
		'contenu',
		'fichier',
		'fichier_type',
		'fichier_size',
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

	public function getFileUrlAttribute()
	{
		return $this->fichier ? Storage::url($this->fichier) : null;
	}
}
