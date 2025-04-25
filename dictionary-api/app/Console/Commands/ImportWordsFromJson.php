<?php

namespace App\Console\Commands;

use App\Http\Services\WordService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportWordsFromJson extends Command
{
    protected $signature = 'app:import-words-from-json';

    protected $description = 'Import Words From json';

    public function handle(WordService $wordService): void
    {
        $jsonString = file_get_contents(base_path('words_dictionary.json'));

        $data = json_decode($jsonString, true);

        $wordService->CreateMulti($data);
    }
}
