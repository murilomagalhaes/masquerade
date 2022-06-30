
<div align="center">
<h1 style="margin-bottom: 0px">🦝 Masquerade</h1> 
<span>A PHP Text Manipulation Library</span>

<br></br>

[![Static Analisys (PHP-STAN)](https://github.com/murilomagalhaes/masquerade/actions/workflows/static_analysis.yml/badge.svg)](https://github.com/murilomagalhaes/masquerade/actions/workflows/static_analysis.yml)


**Masquearde is a library with a handful of methods to help you work with text manipulation, so you (hopefully) don't need to google regex expressions for the thousandth time. It is intended to manipulate short strings such as form inputs and alike, even providing masking/formatting methods.**

</div>

<hr></hr>

## Requirements
`PHP: ^7.4|8.*`
  
## Instalation
 `composer require murilomagalhaes\masquerade`

## Usage
- Once the package is installed, call it on your working file. 
``` php
use Masquerade\Masquerade
```
- Use the `set()` method to start chaining filters, masks, and/or whatever other methods available
``` php
Masquerade::set("Phone: (Brazil) +55 61999995555'")->only('numbers')->mask('## (##) #####-####')->getText();
// Returns: 55 (61) 99999-5555
```

## Examples

- Filtering
``` php

Masquerade::set("I got 99 problemas but regex ain't one!")->only('letters', 'whitespaces')->getText();
// Returns: "I got problems  but regex ain't one!"

Masquerade::set("Phone: +55 00 99999-5555")->only('numbers')->getText();
// Returns "5500999995555"

Masquerade::set("Assistant (to the) regional manager")->between('(', ')')->getText();
// Returns "to the"
```


- Masking | Formatting 
``` php
Masquerade::set("Phone: (Brazil) +55 61999995555'")->only('numbers')->mask('## (##) #####-####')->getText();
// Returns: "55 (61) 99999-5555"

Masquerade::set("00011122234")->only('numbers')->mask('###.###.###-##')->getText();

// Returns: "000.111.222-34"
```

## Available Methods
- `set(string $text): Masquerade` Creates a new Masquerade instance, and defines the text string to be used by the chained methods;
- `between(string $before, string $after): Masquerade` Removes everything outside the defined `$before` and `$after` parameters;
- `only(...$filter_types): Masquerade` Removes characters not defined on the `$filter_types` parameter. 
- `mask(string $pattern): Masquerade` Applies the defined pattern to the string
- `format(string $pattern): Masquerade` Alias to the mask method
- `getText(): string` Returns the text string
- `getOriginalText(): string` Returns the text string before on it's original state
- `getUnmaskedText(): string` Returns the text string before maskking


## Available Filters on the `only()` method.
- 'letters'
- 'numbers'
- 'whitespaces'

## To be implemented
- `removeAccents()` method
- `without_accents` filter

## Known Bugs
- The `letters` filter isn't catching some characters like ÷ and ø that are between the 'À-ú' on the unicode table. Ref: https://unicode-table.com/en/#basic-latin
