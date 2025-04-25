<?php

namespace App\Http\Repositories;

use App\Models\Words;
class WordsRepository
{
    public function create(string $name): void
    {
        $word = new Words();
        $word->name = $name;
        $word->save();
    }

    public function get(string $word)
    {
        return Words::query()
            ->where('name', '=', $word)
            ->first();
    }

    public function getAll(?string $search, ?string $limit)
    {
        $words = Words::query();

        if ($search) {
            $words = $words->where('name', 'like', "%$search%");
        }

        return $limit ? $words->paginate($limit) : $words->get();
    }
}
