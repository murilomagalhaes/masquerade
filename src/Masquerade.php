<?php

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
     * @return self
     */
    public static function set(string $text): self
    {
        return new self($text);
    }

    /**
     * Returns the text string before maskking
     * @return string
     */
    public function getUnmaskedText(): string
    {
        return $this->unmasked_text ?? '';
    }

    /**
     * Returns the text string on it's original state
     * @return string
     */
    public function getOriginalText(): string
    {
        return $this->original_text ?? '';
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
     * Returns the text string;
     * @return string
     */
    public function getText(): string
    {
        $this->trim();
        return $this->text;
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
     * @param mixed $arguments Function's arguments
     * @return callable|self|string
     */
    public function __call(string $function, mixed $arguments): callable|self|string
    {
        if (isset(static::$macros[$function])) {
            return static::$macros[$function]($this);
        }

        return $this->$function($arguments);
    }
}
