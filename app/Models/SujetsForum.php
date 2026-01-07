<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SujetsForum
 * 
 * @property int $id
 * @property int $forum_id
 * @property int $user_id
 * @property string $titre
 * @property string $contenu
 * @property Carbon $created_at
 * 
 * @property Forum $forum
 * @property User $user
 * @property Collection|MessagesForum[] $messages_forums
 *
 * @package App\Models
 */
class SujetsForum extends Model
{
	protected $table = 'sujets_forum';
	public $timestamps = false;

	protected $casts = [
		'forum_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'forum_id',
		'user_id',
		'titre',
		'contenu'
	];

	public function forum()
	{
		return $this->belongsTo(Forum::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function messages_forums()
	{
		return $this->hasMany(MessagesForum::class, 'sujet_id');
	}

	public function messages()
    {
        return $this->messages_forums();
    }
	public function auteur()
    {
        return $this->user();
    }
}
