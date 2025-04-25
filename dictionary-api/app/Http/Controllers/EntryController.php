<?php
namespace App\Http\Controllers;

use App\Http\Services\FavoriteService;
use App\Http\Services\WordService;
use App\Models\Entry;
use App\Http\Services\UserService;
use App\Http\Services\WordsApiService;
use Illuminate\Http\Request;
use Throwable;

class EntryController extends Controller
{
    private WordsApiService $wordsApiService;
    private WordService $wordService;
    private FavoriteService $favoriteService;
    private UserService $userService;

    public function __construct(
        WordsApiService      $wordsApiService,
        WordService     $wordService,
        FavoriteService $favoriteService,
        UserService     $userService
    )
    {
        $this->wordsApiService = $wordsApiService;
        $this->wordService = $wordService;
        $this->favoriteService = $favoriteService;
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit = (int) $request->query('limit', 10);
        $page = (int) $request->query('page', 1);

        $query = Entry::query();

        if ($search) {
            $query->where('word', 'like', '%' . $search . '%');
        }

        $paginator = $query->orderBy('word')->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'results' => $paginator->pluck('word'),
            'totalDocs' => $paginator->total(),
            'page' => $paginator->currentPage(),
            'totalPages' => $paginator->lastPage(),
            'hasNext' => $paginator->hasMorePages(),
            'hasPrev' => $paginator->currentPage() > 1,
        ]);
    }

    public function find(string $word)
    {
        try {

            return response()->json($this->wordsApiService->fetchWord($word));

        } catch (Throwable $t) {

            return response()->json([
                'status'  => false,
                'message' => 'internal server error',
            ], 400);
        }
    }

    public function saveFavorite(string $word){
        try {

            $word = $this->wordService->get($word);

            if ($word) {
                $user = $this->userService->getCurrentUser();

                return response()->json($this->favoriteService->create($user->id, $word->id));
            }

            return response()->json(['message' => 'word not founded'], 404);

        } catch (Throwable $t) {

            return response()->json([
                'status'  => false,
                'message' => 'internal server error',
            ], 400);
        }
    }

    public function deleteFavorite(string $word){
        try {

            $word = $this->wordService->get($word);

            if ($word) {

                $user = $this->userService->getCurrentUser();

                return response()->json($this->favoriteService->delete($user->id, $word->id));
            }

            return response()->json(['message' => 'word not found']);

        } catch (Throwable $t) {

            return response()->json([
                'status'  => false,
                'message' => 'internal server error',
            ], 400);
        }
    }

}
