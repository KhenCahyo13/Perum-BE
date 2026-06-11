<?php

namespace Modules\Core\Services;

class RedisService
{
    // ─── Key builders ─────────────────────────────────────────────────────────

    public static function houseListKey(array $params): string
    {
        ksort($params);
        return 'houses:list:' . http_build_query(array_filter($params, fn ($v) => $v !== null && $v !== ''));
    }

    public static function houseDetailKey(string $id): string
    {
        return sprintf('houses:detail:%s', $id);
    }

    public static function houseStatsKey(): string
    {
        return 'houses:stats';
    }

    public static function residentListKey(array $params): string
    {
        ksort($params);
        return 'residents:list:' . http_build_query(array_filter($params, fn ($v) => $v !== null && $v !== ''));
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
}
