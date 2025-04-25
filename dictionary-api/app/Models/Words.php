<?php

namespace App\Models;

use App\Models\Favorite;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property-read Collection $histories
 * @property-read Collection $favorites
 */

class Words extends Model
{

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
