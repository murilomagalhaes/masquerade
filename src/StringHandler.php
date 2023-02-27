<?php

declare(strict_types=1);

namespace Masquerade;

use Masquerade\Helpers\RegexHelper;

class StringHandler
{
    /**
     * Text string that methods will be applied
     * @var string $text
     */
    protected string $text;

    /**
     * Text string before applying the mask/format method
     * @var string $unmasked_text
     */
    public string $unmasked_text;

    /**
     * Text string before applying any method
     * @var string $original_text
     */
    public string $original_text;

    /**
     * Characters to keep \ ignore while filtering
     * @var string $characters_to_keep
     */
    public string $characters_to_ignore = '';

    /**
     * Excpected filter types. ['numbers', 'letters', 'whitespaces']
     * @var array<int,string> $excpected_filter_types
     */
    protected array $excpected_filter_types = [];


    public function __construct(string $text)
    {
        $this->text = $this->unmasked_text = $this->original_text = $text;
        $this->excpected_filter_types = ['numbers', 'letters', 'whitespaces', 'punctuation'];
    }

    /**
     * The defined characters won't be removed by the only(). Must be called BEFORE the only() method to take effect
     * @param string $character
     */
    public function ignore(string ...$character): StringHandler
    {
        $this->characters_to_ignore = preg_quote(implode('', $character));
        return $this;
    }

    /**
     * Removes characters not defined on the $filter_types parameter
     * @param string $filter_types Available types ['numbers', 'letters', 'whitespaces']
     * @return StringHandler
     */
    public function only(string ...$filter_types): StringHandler
    {
        $unexpected_filters = array_diff($filter_types, $this->excpected_filter_types);

        if ($unexpected_filters) {
            throw new \Exception("Unexcpeted filter type '{$unexpected_filters[array_key_first($unexpected_filters)]}' on 'only()' method.");
        }

        $regex = '';

        foreach ($filter_types as $filter) {
            $match_filter_helper_function = "match_$filter";
            $regex .= RegexHelper::$match_filter_helper_function();
        }

        $matches = [];
        preg_match_all("/[$regex{$this->characters_to_ignore}]/", $this->text, $matches);

        $this->text = implode('', $matches[0]);

        // Removes the &#247; character not filtered by the 'letters' regex
        if (in_array('letters', $filter_types)) {
            $this->text = str_replace('÷', '', $this->text);
        }

        return $this;
    }

    /**
     * Removes the defined characters from the text string
     * @param string $characters Characters to be removed from the string 
     * @return StringHandler
     */
    public function strip(string ...$characters): StringHandler
    {
        $this->text = str_replace($characters, '', $this->text);
        return $this;
    }

    /**
     * Removes trailing and multiple spaces/tabs from the string
     * @return StringHandler
     */
    public function trim(): StringHandler
    {
        $this->text = trim(preg_replace("/\s+/", ' ', $this->text) ?? '');
        return $this;
    }

    /**
     * Remove characters outside the $before and $after parameters
     * @param string $before
     * @param string $after
     * @return StringHandler
     */
    public function between(string $before, string $after): StringHandler
    {
        $matches = [];
        $regex = RegexHelper::match_between($before, $after);
        preg_match_all("/$regex/", $this->text, $matches);

        $this->text = implode('', $matches[0]);
        $this->text = substr($this->text, 1, strlen($this->text) - 2);

        return $this;
    }

    /**
     * Returns the formated string based on the declared pattern.
     * @param string $pattern Ex: '####/##/##'
     * @return StringHandler
     */
    public function format(string $pattern): StringHandler
    {
        $this->unmasked_text = $this->text;

        foreach (str_split($pattern) as $i => $char) {
            if ($char !== '#' && $char !== substr($this->unmasked_text, $i, 1)) {
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
     * @return StringHandler
     */
    public function mask(string $pattern): StringHandler
    {
        return $this->format($pattern);
    }

    /**
     * Remove accents acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml 
     * @return StringHandler
     */
    public function removeAccents(): StringHandler
    {
        $this->text = preg_replace(
            '~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i',
            '$1',
            htmlentities($this->text, ENT_QUOTES, 'UTF-8')
        ) ?? '';

        return $this;
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
     * Returns the text string;
     * @return string
     */
    public function getText(): string
    {
        $this->trim();
        return $this->text;
    }
}
