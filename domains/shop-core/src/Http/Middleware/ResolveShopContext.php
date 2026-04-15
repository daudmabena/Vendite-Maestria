<?php

declare(strict_types=1);

namespace Modules\ShopCore\Http\Middleware;

use Modules\ShopCore\Models\Channel;
use Modules\ShopCore\Services\ShopContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveShopContext
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $channelCode = $request->header('X-Channel-Code')
            ?? $request->query('channel_code');

        $channel = null;
        if (is_string($channelCode) && $channelCode !== '') {
            $channel = Channel::query()
                ->where('enabled', true)
                ->where('code', $channelCode)
                ->first();
        }

        if ($channel === null && $request->getHost() !== '') {
            $channel = Channel::query()
                ->where('enabled', true)
                ->where('hostname', $request->getHost())
                ->first();
        }

        if ($channel !== null) {
            app(ShopContext::class)->setChannel($channel);
            $localeCode = $channel->defaultLocale?->code;
            if (is_string($localeCode) && $localeCode !== '') {
                app(ShopContext::class)->setLocaleCode($localeCode);
                app()->setLocale($localeCode);
            }
        }

        $explicitLocale = $request->header('X-Locale-Code') ?? $request->query('locale_code');
        if (is_string($explicitLocale) && $explicitLocale !== '') {
            app(ShopContext::class)->setLocaleCode($explicitLocale);
            app()->setLocale($explicitLocale);
        }

        return $next($request);
    }
}

