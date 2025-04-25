<?php
namespace App\Http\Services;

use App\Http\Repositories\HistoriesRepository;
use App\Http\Repositories\UsersRepository;
use App\Http\Repositories\WordsRepository;
use Illuminate\Support\Facades\Http;

class WordsApiService
{
    private WordsRepository $wordsRepository;
    private UsersRepository $usersRepository;
    private HistoriesRepository $historiesRepository;

    public function __construct(
        WordsRepository    $wordsRepository,
        UsersRepository    $usersRepository,
        HistoriesRepository $historiesRepository
    )
    {
        $this->wordsRepository = $wordsRepository;
        $this->usersRepository = $usersRepository;
        $this->historiesRepository = $historiesRepository;
    }


    public function fetchWord($word)
    {
        $baseUrl = env('DICTIONARY_URL');
        $response = Http::get($baseUrl . $word)->json();

        $user = $this->usersRepository->getCurrentUser();
        $word = $this->wordsRepository->get($word);
        $this->historiesRepository->create($user->id, $word->id);

        $details = [];

        $meanings = $response[0]['meanings'];

        foreach ($meanings as $meaning) {

            foreach ($meaning['definitions'] as $def) {

                $details[] = $def['definition'];
            }
        }

        return [
            'details' => $details,
        ];

    }
}
