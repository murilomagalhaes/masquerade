<?php

namespace Masquerade;

use Masquerade\Traits\RegexHelpers;

/**
 * Main class containing all library's methods.
 */
class Masquerade
{
    use RegexHelpers;

    /**
     * Text string that methods will be applied
     * @var string $text
     */
    private string $text;

    /**
     * Text string before applying any method
     * @var string $original_text
     */
    public string $original_text;

    /**
     * Text string before applying the mask/format method
     * @var string $unmasked_text
     */
    public string $unmasked_text;

    /**
     * Excpected filter types. ['numbers', 'letters', 'whitespaces']
     * @var array<int,string> $excpected_filter_types
     */
    private array $excpected_filter_types = [];


    public function __construct(string $text)
    {
        $this->text = $text;
        $this->original_text = $text;
        $this->excpected_filter_types = ['numbers', 'letters', 'whitespaces'];
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
     * Removes characters not defined on the $filter_types parameter
     * @param string $filter_types Available types ['numbers', 'letters', 'whitespaces']
     * @return self
     */
    public function only(string ...$filter_types): self
    {
        $unexpected_filters = array_diff($filter_types, $this->excpected_filter_types);

        if ($unexpected_filters) {
            throw new \Exception("Unexcpeted filter type '{$unexpected_filters[array_key_first($unexpected_filters)]}' on 'only()' method.");
        }

        $regex = '';

        foreach ($filter_types as $filter) {
            $match_filter_trait_function = "match_$filter";
            $regex .= $this->$match_filter_trait_function();
        }

        $matches = [];
        preg_match_all("/[$regex]+/", $this->text, $matches);

        $this->text = implode('', $matches[0]);

        return $this;
    }

    /**
     * Remove characters outside the $before and $after parameters
     * @param string $before
     * @param string $after
     * @return self
     */
    public function between(string $before, string $after): self
    {
        $matches = [];
        $regex = $this->match_between($before, $after);
        preg_match_all("/$regex/", $this->text, $matches);

        $this->text = implode('', $matches[0]);
        $this->text = substr($this->text, 1, strlen($this->text) - 2);

        return $this;
    }

    /**
     * Returns the formated string based on the declared pattern.
     * @param string $pattern Ex: '####/##/##'
     * @return self
     */
    public function format(string $pattern): self
    {

        $this->unmasked_text = $this->text;

        foreach (str_split($pattern) as $i => $char) {
            if ($char !== '#') {
                $this->text = substr_replace($this->text, $char, $i, 0);
            }
        }

        $this->text = substr($this->text, 0, strlen($pattern));

        return $this;
    }

    /**
     * Alias for the format function.
     * Returns the formated string based on the declared pattern.
     * @param string $pattern Ex: '####/##/##'
     * @return self
     */
    public function mask(string $pattern): self
    {
        return $this->format($pattern);
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
        return $this->ori ?? '';
    }

    /**
     * Returns the text string;
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    public function __toString()
    {
        return $this->text;
    }
}
