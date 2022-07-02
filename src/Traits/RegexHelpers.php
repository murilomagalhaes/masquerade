<?php

namespace Masquerade\Traits;

trait RegexHelpers
{
    public function match_numbers(): string
    {
        return '0-9';
    }

    public function match_letters(): string
    {
        return "A-Za-zÀ-ÿ";
    }

    public function match_whitespaces(): string
    {
        return '\s';
    }

    public function match_between(string $before, string $after): string
    {
        return "\\$before(.*?)\\$after";
    }
}
