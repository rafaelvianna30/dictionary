<?php

namespace App\Http\Services;

use App\Http\Repositories\UsersRepository;
use App\Models\Favorite;
use App\Models\History;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{
    private UsersRepository $userRepository;

    public function __construct(UsersRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getCurrentUser()
    {
        return $this->userRepository->getCurrentUser();
    }

    public function create(Request $request): array
    {
        return $this->userRepository->create($request);
    }

    public function authenticate(Request $request): array
    {
        $data = [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($data)) {

            return [
                'message' => trans('validation.token_generated'),
                'data' => [
                    'token' => Auth::user()->createToken('API')->accessToken,
                ]
            ];
        }

        return [
            'message' => trans('validation.invalid_credentials'),
        ];
    }

    public function show(): array
    {
        $user = $this->getCurrentUser();

        return [
            'name'  => $user->name,
            'email' => $user->email,
            'added' => $user->created_at->format('d/m/Y H:i:s'),
        ];
    }

    public function history(): array
    {
        $user = $this->getCurrentUser();

        $data = [];

        /* @var History $history */

        foreach ($user->histories as $history) {

            $data[] = [
                'word'  => $history->word->name,
                'added' => $history->created_at->format('d/m/Y H:i:s'),
            ];
        }

        return $data;
    }

    public function favorites(): array
    {
        $user = $this->getCurrentUser();

        $favorites = $user->favorites->all();

        $data = [];

        /* @var Favorite $favorite */

        foreach ($favorites as $favorite) {

            $data[] = [
                'word'  => $favorite->word->name,
                'added' => $favorite->created_at->format('d/m/Y H:i:s'),
            ];
        }

        return $data;
    }
}
