<?php

namespace App\Models;

use App\Models\User;
use App\Models\Words;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $word_id
 * @property Carbon $created_at
 * @property-read User $user
 * @property-read Words $word
 */

class Favorite extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function word()
    {
        return $this->belongsTo(Words::class);
    }
}
