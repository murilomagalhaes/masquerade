<?php

declare(strict_types=1);

namespace Masquerade;

class Masquerade extends StringHandler
{
    /**
     * Macro functions array
     * @var array<string,callable>
     */
    public static array $macros = [];

    public function __construct(string $text)
    {
        parent::__construct($text);
    }

    /**
     * Creates a new Masquerade instance, and defines the text string to be used by the chained methods;
     * @param string $text
     * @return Masquerade
     */
    public static function set(string $text): Masquerade
    {
        return new self($text);
    }

    /**
     * Defines macro function.
     * @param string $name Macro function's name
     * @param callable $macro Callback function 
     */
    public static function macro(string $name, callable $macro): void
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Returns text string on current state
     * @return string Text string
     */
    public function __toString(): string
    {
        $this->trim();
        return $this->text;
    }

    /**
     * Override function calls to search for defined macro functions
     * @param string $function Function name
     * @param array<int,callable> $arguments Function's arguments
     * @return Masquerade
     */
    public function __call(string $function, array $arguments): Masquerade
    {
        if (isset(static::$macros[$function])) {
            static::$macros[$function]($this);
            return $this;
        }

        return $this->$function($arguments);
    }
}
