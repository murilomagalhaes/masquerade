<?php

namespace Masquerade\Helpers;

final class RegexHelper
{
    public static function match_numbers(): string
    {
        return '0-9';
    }

    public static function match_letters(): string
    {
        return "A-Za-zÀ-ÿ";
    }

    public static  function match_whitespaces(): string
    {
        return '\s';
    }

    public static function match_between(string $before, string $after): string
    {
        return "\\$before(.*?)\\$after";
    }

    public static function match_punctuation(): string
    {
        return ",.:;?¿!¡\-";
    }
}
