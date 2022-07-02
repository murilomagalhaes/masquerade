<?php

namespace Masquerade;

use Masquerade\Traits\RegexHelpers;

class StringHandler
{
    use RegexHelpers;

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
     * Excpected filter types. ['numbers', 'letters', 'whitespaces']
     * @var array<int,string> $excpected_filter_types
     */
    protected array $excpected_filter_types = [];


    public function __construct(string $text)
    {
        $this->text = $this->unmasked_text = $this->original_text = $text;
        $this->excpected_filter_types = ['numbers', 'letters', 'whitespaces'];
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
        preg_match_all("/[$regex]/", $this->text, $matches);

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
     * @return self
     */
    public function strip(string ...$characters): self
    {
        $this->text = str_replace($characters, '', $this->text);
        return $this;
    }

    /**
     * Removes trailing and multiple spaces/tabs from the string
     */
    public function trim(): self
    {
        $this->text = trim(preg_replace("/\s+/", ' ', $this->text));
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
     * Remove accents acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml 
     * @return self
     */
    public function removeAccents(): self
    {
        $this->text = preg_replace(
            '~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i',
            '$1',
            htmlentities($this->text, ENT_QUOTES, 'UTF-8')
        ) ?? '';

        return $this;
    }
}
