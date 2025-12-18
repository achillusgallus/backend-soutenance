<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Forum
 * 
 * @property int $id
 * @property int $matiere_id
 * @property string $titre
 * @property int $created_by
 * @property Carbon $created_at
 * 
 * @property Matiere $matiere
 * @property User $user
 * @property Collection|SujetsForum[] $sujets_forums
 *
 * @package App\Models
 */
class Forum extends Model
{
	protected $table = 'forums';
	public $timestamps = false;

	protected $casts = [
		'matiere_id' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'matiere_id',
		'titre',
		'created_by'
	];

	public function matiere()
	{
		return $this->belongsTo(Matiere::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function sujets_forums()
	{
		return $this->hasMany(SujetsForum::class);
	}
}
