<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MessagesForum
 * 
 * @property int $id
 * @property int $sujet_id
 * @property int $user_id
 * @property string $message
 * @property Carbon $created_at
 * 
 * @property SujetsForum $sujets_forum
 * @property User $user
 *
 * @package App\Models
 */
class MessagesForum extends Model
{
	protected $table = 'messages_forum';
	public $timestamps = false;

	protected $casts = [
		'sujet_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'sujet_id',
		'user_id',
		'message'
	];

	public function sujets_forum()
	{
		return $this->belongsTo(SujetsForum::class, 'sujet_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
