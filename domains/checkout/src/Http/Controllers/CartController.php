<?php

declare(strict_types=1);

namespace Modules\Checkout\Http\Controllers;

use Modules\Checkout\Http\Requests\AddCartItemRequest;
use Modules\Checkout\Http\Requests\ApplyCartCouponRequest;
use Modules\Checkout\Http\Requests\CheckoutCartRequest;
use Modules\Checkout\Http\Requests\StoreCartRequest;
use Modules\Checkout\Http\Requests\UpdateCartItemRequest;
use Modules\Checkout\Http\Requests\UpdateCartShippingMethodRequest;
use Modules\Checkout\Http\Resources\CartResource;
use Modules\Checkout\Models\Order;
use App\Models\User;
use Modules\Checkout\Services\CartService;
use Modules\Checkout\Workflow\Enums\OrderState;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function store(StoreCartRequest $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();
        $order = $this->cartService->createCart(
            (int) $request->validated('channel_id'),
            $user?->customer?->id,
        );

        return CartResource::make($order->load(['items', 'adjustments']))->response()->setStatusCode(201);
    }

    public function show(string $token): JsonResponse
    {
        $order = $this->findOpenCart($token);

        return CartResource::make($order->load(['items', 'adjustments']))->response();
    }

    public function addItem(AddCartItemRequest $request, string $token): JsonResponse
    {
        $order = $this->findOpenCart($token);
        $this->cartService->addItem(
            $order,
            (int) $request->validated('product_variant_id'),
            (int) $request->validated('quantity'),
        );

        return CartResource::make($order->fresh()->load(['items', 'adjustments']))->response();
    }

    public function updateItem(UpdateCartItemRequest $request, string $token, int $item): JsonResponse
    {
        $order = $this->findOpenCart($token);
        $this->cartService->setItemQuantity($order, $item, (int) $request->validated('quantity'));

        return CartResource::make($order->fresh()->load(['items', 'adjustments']))->response();
    }

    public function removeItem(string $token, int $item): JsonResponse
    {
        $order = $this->findOpenCart($token);
        $this->cartService->removeItem($order, $item);

        return CartResource::make($order->fresh()->load(['items', 'adjustments']))->response();
    }

    public function applyCoupon(ApplyCartCouponRequest $request, string $token): JsonResponse
    {
        $order = $this->findOpenCart($token);
        $this->cartService->applyCoupon($order, (string) $request->validated('code'));

        return CartResource::make($order->fresh()->load(['items', 'adjustments']))->response();
    }

    public function removeCoupon(string $token): JsonResponse
    {
        $order = $this->findOpenCart($token);
        $this->cartService->removeCoupon($order);

        return CartResource::make($order->fresh()->load(['items', 'adjustments']))->response();
    }

    public function updateShipping(UpdateCartShippingMethodRequest $request, string $token): JsonResponse
    {
        $order = $this->findOpenCart($token);
        $this->cartService->selectShippingMethod($order, (int) $request->validated('shipping_method_id'));

        return CartResource::make($order->fresh()->load(['items', 'adjustments']))->response();
    }

    public function checkout(CheckoutCartRequest $request, string $token): JsonResponse
    {
        $order = $this->findOpenCart($token);
        $result = $this->cartService->checkout($order, (int) $request->validated('payment_method_id'));

        $orderFresh = $result['payment']->order->fresh()->load(['items', 'adjustments']);

        return response()->json([
            'cart' => CartResource::make($orderFresh)->resolve(),
            'payment' => [
                'id' => $result['payment']->id,
                'state' => $result['payment']->state,
                'amount' => $result['payment']->amount,
                'details' => $result['payment']->details,
            ],
            'processor_result' => $result['processor_result'],
        ]);
    }

    private function findOpenCart(string $token): Order
    {
        $order = Order::query()
            ->where('token_value', $token)
            ->where('state', OrderState::Cart->value)
            ->whereNull('checkout_completed_at')
            ->first();

        if ($order === null) {
            throw new NotFoundHttpException('Cart not found.');
        }

        return $order;
    }
}
