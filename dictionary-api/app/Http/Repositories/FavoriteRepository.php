<?php

namespace App\Http\Repositories;

use App\Models\Favorite;

class FavoriteRepository
{
    public function get(int $userId, int $wordId)
    {
        return Favorite::query()
            ->where('user_id', '=', $userId)
            ->where('word_id', '=', $wordId)
            ->first();
    }

    public function create(int $userId, int $wordId): array
    {
        $favorite = $this->get($userId, $wordId);

        if ($favorite) {

            return [
                'message' =>  'already favorited',
            ];
        }

        $favorite = new Favorite();
        $favorite->user_id = $userId;
        $favorite->word_id = $wordId;
        $favorite->save();

        return [
            'message' => 'favorited word added',
        ];
    }

    public function delete(int $userId, int $wordId): array
    {
        $favorite = $this->get($userId, $wordId);

        if ($favorite) {
            $favorite->delete();

            return [
                'message' => 'unfavorited word'
            ];
        }

        return [
            'message' => 'not favorite'
        ];
    }

}
