<div align="center">
<h1 style="margin-bottom: 0px">🦝 Masquerade</h1> 
<span>A PHP Text Manipulation Library</span>
</div>

<br>

[![Static Analisys (PHP-STAN)](https://github.com/murilomagalhaes/masquerade/actions/workflows/static_analysis.yml/badge.svg)](https://github.com/murilomagalhaes/masquerade/actions/workflows/static_analysis.yml)

*Clique **[aqui](README_PT.md)** caso queira acessar a versão em **Português!** deste arquivo.* 🇵🇹 🇧🇷

**Masquerade is a library with a handful of methods to help you work with text manipulation, so you (hopefully) don't need to google regex expressions for the thousandth time. It is intended to manipulate short strings such as form inputs and alike, even providing masking/formatting methods.**


## Requirements
- `PHP: ^7.4|8.*`
- `Composer`

  
## Instalation
 `composer require murilomagalhaes/masquerade`

## Usage
- Once the package is installed, call it on your working file. 
``` php
use Masquerade\Masquerade
```
- Use the `set()` method to start chaining filters, masks, and/or whatever other methods available
``` php
Masquerade::set("Phone: (Brazil) +55 61999995555'")
    ->only('numbers')
    ->mask('## (##) #####-####')
    ->getText(); // Returns: "55 (61) 99999-5555"
```

## Examples

- Filtering
``` php

Masquerade::set("I got 99 problems but regex ain't one!")
    ->only('letters', 'whitespaces')
    ->getText(); // Returns: "I got problems but regex aint one!"

Masquerade::set("Phone: +55 00 99999-5555")
    ->only('numbers')
    ->getText(); // Returns "5500999995555"

Masquerade::set("Assistant (to the) regional manager")
    ->between('(', ')')
    ->getText(); // Returns "to the"

Masquerade::set("Hello, Universe")
    ->strip('Hello,')
    ->getText(); // Returns: "Universe"

Masquerade::set("Hablo Español y Português")
    ->removeAccents()
    ->getText(); // Returns: "Hablo Espanol y Portugues"
```

- Masking | Formatting 
``` php
Masquerade::set("Phone: (Brazil) +55 00999995555'")
    ->only('numbers')
    ->mask('## (##) #####-####')
    ->getText(); // Returns: "55 (00) 99999-5555"

Masquerade::set("00011122234") 
    ->only('numbers')
    ->mask('###.###.###-##')
    ->getText(); // Returns: "000.111.222-34"
```

- Custom methods (Macros)
``` php
Masquerade::macro('maskAsPhone', function($instance){
    return $instance->only('numbers')->mask('(##) #####-####');
});

Masquerade::set('Number: 00999995555')
    ->maskAsPhone()
    ->getText(); // Returns: "(00) 99999-5555"
```

- Getters

```php
$text = Masquerade::set('YMCA');

$text->mask('#-#-#-#');

$text->getText(); // Returns: "Y-M-C-A"
$text->getUnmaskedText(); // Returns: "YMCA"
```



## Available Methods

| Method Signature | Description | 
|---|---|
| `set(string $text): Masquerade` | Creates a new Masquerade instance, and defines the text string to be used by the chained methods. |
| `only(...$filter_types): Masquerade` | Removes character types NOT defined on the `$filter_types` parameter. Available filters: `'letters'`, `'numbers'` and `'whitespaces'` |
| `strip(...$characters): Masquerade` | Removes the defined characters from the text string |
| `between(string $before, string $after): Masquerade`  | Removes everything outside the defined characters on the `$before` and `$after` parameters;  |
| `removeAccents(): Masquerade`| Remove string's accents. <br>(acute\|cedil\|circ\|grave\|lig\|orn\|ring\|slash\|th\|tilde\|uml\|)
| `mask(string $pattern): Masquerade`| Applies the defined pattern to the text string |
| `format(string $pattern): Masquerade` | Alias to the mask method |
| `trim(): Masquerade` | Removes trailing and multiple spaces/tabs from the text string <br>(Method always aplied on class __toString() and getText() methods)|
| `static::macro(string $name, callable $callback): void`| Defines a macro/custom method |
| `getText(): string` | Returns the text string |
| `getOriginalText(): string` | Returns the text string on it's original state |
| `getUnmaskedText(): string` | Returns the text string before maskking |

## Coming soon
- Punctuation filter to `only()` method.
- Add character filter exceptions to `only()` method.