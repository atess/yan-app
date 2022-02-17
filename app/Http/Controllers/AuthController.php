<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Requests\Auth\EmailVerifyRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\AuthApiResponse;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use AuthApiResponse;

    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->userRepository = $repository;
    }

    /**
     * Yeni kullanıcı kayıt
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $emailVerificationCode = random_int(1000, 9999);

        $user = $this->userRepository->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'email_verification_code' => $emailVerificationCode,
        ]);

        Log::info("email_verification_code: $emailVerificationCode");

        event(new Registered($user));

        return $this->authResponse($user, 201, __('auth.account_created'));
    }

    /**
     * E-posta adresi doğrulama
     *
     * @param EmailVerifyRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function emailVerify(EmailVerifyRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (!$this->userRepository->check($validated))
            throw new Exception(__('auth.failed'), 401);

        $user = $this->userRepository->findMail($validated['email']);
        $user->markEmailAsVerified();

        event(new Verified($user));

        return $this->authResponse($user, 200, __('auth.email_verified'));
    }

    /**
     * Oturum aç
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws Exception
     * @group Authentication
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->userRepository->findMail($validated['email']);

        if (!Auth::attempt($validated))
            throw new Exception(__('auth.failed'), 401);

        return $this->authResponse($user, 200, __('auth.login_successfully'));
    }
}
