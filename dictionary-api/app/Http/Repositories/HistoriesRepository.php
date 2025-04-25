<?php

namespace App\Http\Repositories;

use App\Models\History;

class HistoriesRepository
{
    public function get(int $userId, int $wordId)
    {
        return History::query()
            ->where('user_id', '=', $userId)
            ->where('word_id', '=', $wordId)
            ->first();
    }

    public function create(int $userId, int $wordId): void
    {
        $history = $this->get($userId, $wordId);

        if (!$history) {
            $history = new History();
            $history->user_id = $userId;
            $history->word_id = $wordId;
            $history->save();
        }
    }
}
