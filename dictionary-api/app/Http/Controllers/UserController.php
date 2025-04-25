<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showUser()
    {
        try {

            return response()->json($this->userService->show());

        } catch (Throwable $t) {

            return response()->json([
                'status' => false,
                'message' => 'internal server error',
            ], 400);
        }
    }

    public function showHistory(Request $request)
    {
        try {
            $page = 1;
            $limit =10;
            $data = collect($this->userService->history());

            $paginator = new LengthAwarePaginator(
                $data->forPage($page, $limit)->values(),
                $data->count(),
                $limit,
                $page,
                ['path' => url()->current()]
            );

            $results = $data
                ->forPage($page, $limit)
                ->map(fn ($item) => [
                    'word' => $item['word'],
                    'added' => $item['added'],
                ])
                ->values();
            return response()->json([
                'results' => $results,
                'totalDocs' => $paginator->total(),
                'page' => $paginator->currentPage(),
                'totalPages' => $paginator->lastPage(),
                'hasNext' => $paginator->hasMorePages(),
                'hasPrev' => $paginator->currentPage() > 1,
            ]);

        } catch (Throwable $t) {

            return response()->json([
                'status' => false,
                'message' => 'internal server error',
            ], 400);
        }
    }

    public function showFavorites()
    {
        try {
            $page = 1;
            $limit =10;
            $data = collect($this->userService->favorites());

            $paginator = new LengthAwarePaginator(
                $data->forPage($page, $limit)->values(),
                $data->count(),
                $limit,
                $page,
                ['path' => url()->current()]
            );

            $results = $data
                ->forPage($page, $limit)
                ->map(fn ($item) => [
                    'word' => $item['word'],
                    'added' => $item['added'],
                ])
                ->values();
            return response()->json([
                'results' => $results,
                'totalDocs' => $paginator->total(),
                'page' => $paginator->currentPage(),
                'totalPages' => $paginator->lastPage(),
                'hasNext' => $paginator->hasMorePages(),
                'hasPrev' => $paginator->currentPage() > 1,
            ]);

        } catch (Throwable $t) {

            return response()->json([
                'status' => false,
                'message' => 'internal server error',
            ], 400);
        }
    }
}
