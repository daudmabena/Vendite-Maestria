<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Customer\Http\Requests\Auth\LoginRequest;
use Modules\Customer\Http\Requests\Auth\RegisterRequest;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'subscribed_to_newsletter' => $validated['subscribed_to_newsletter'] ?? false,
        ]);

        $token = $user->createToken('customer-api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'customer' => CustomerResource::make($customer),
        ], 201);
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::query()->where('email', $validated['email'])->first();

        if ($user === null || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->customer === null) {
            throw ValidationException::withMessages([
                'email' => ['No customer profile is linked to this account.'],
            ]);
        }

        $token = $user->createToken('customer-api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'customer' => CustomerResource::make($user->customer),
        ]);
    }

    public function me(Request $request): CustomerResource
    {
        /** @var User $user */
        $user = $request->user();

        return CustomerResource::make($user->customer);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(null, 204);
    }
}
