<?php

namespace App\Http\Services;

use App\Http\Repositories\WordsRepository;
use App\Models\Words;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WordService
{
    private WordsRepository $wordRepository;

    public function __construct(WordsRepository $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    public function CreateMulti(array $items): void
    {

        foreach (array_keys($items) as $name) {

            Words::query()->create([
                'name'=>$name,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }
    }

    public function get(string $word)
    {
        return $this->wordRepository->get($word);
    }

    public function getAll(Request $request): array
    {
        $search = $request->input('search');
        $limit = $request->input('limit');

        $words = $this->wordRepository->getAll($search, $limit);

        if ($limit) {

            return [
                'results' => $words->pluck('name')->toArray(),
                'totalDocs' => $words->total(),
                'page' => $words->currentPage(),
                'totalPages' => $words->lastPage(),
                'hasNext' => $words->hasMorePages(),
                'hasPrev' => $words->currentPage() > 1,
            ];
        }

        return [
            'results' => $words->pluck('name')->toArray(),
            'totalDocs' => $words->count(),
            'page' => 1,
            'totalPages' => 1,
            'hasNext' => false,
            'hasPrev' => false,
        ];
    }
}
