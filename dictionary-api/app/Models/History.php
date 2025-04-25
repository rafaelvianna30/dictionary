<?php

namespace App\Models;

use App\Models\User;
use App\Models\Words;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $word_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property-read User $user
 * @property-read Words $word
 */

class History extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function word(): BelongsTo
    {
        return $this->belongsTo(Words::class);
    }
}
