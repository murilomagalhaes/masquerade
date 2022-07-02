<?php

namespace Masquerade;

use Masquerade\Traits\RegexHelpers;

class Masquerade extends StringHandler
{
    use RegexHelpers;

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
     * Returns the text string before on it's original state
     * @return string
     */
    public function getOriginalText(): string
    {
        return $this->original_text ?? '';
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

    public function __toString()
    {
        $this->trim();
        return $this->text;
    }
}
