<?php

namespace Modules\Core\Services;

use Illuminate\Support\Facades\Cache;

class RedisService extends Service
{
    private const DEFAULT_TTL = 3600;

    // ─── Key builders ─────────────────────────────────────────────────────────

    public static function houseListKey(int $page): string
    {
        return sprintf('houses:list:%d', $page);
    }

    public static function houseDetailKey(string $id): string
    {
        return sprintf('houses:detail:%s', $id);
    }

    public static function residentListKey(int $page): string
    {
        return sprintf('residents:list:%d', $page);
    }

    public static function residentDetailKey(string $id): string
    {
        return sprintf('residents:detail:%s', $id);
    }

    // ─── Tag builders ─────────────────────────────────────────────────────────

    public static function houseListTag(): string
    {
        return 'houses';
    }

    public static function houseTag(string $id): string
    {
        return sprintf('house:%s', $id);
    }

    public static function residentListTag(): string
    {
        return 'residents';
    }

    public static function residentTag(string $id): string
    {
        return sprintf('resident:%s', $id);
    }

    // ─── Cache operations ─────────────────────────────────────────────────────

    public function remember(string $key, array $tags, callable $callback, int $ttl = self::DEFAULT_TTL): mixed
    {
        return Cache::tags($tags)->remember($key, $ttl, $callback);
    }

    public function flush(string ...$tags): void
    {
        foreach ($tags as $tag) {
            Cache::tags([$tag])->flush();
        }
    }
}
