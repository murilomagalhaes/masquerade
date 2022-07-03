<div align="center">
<h1 style="margin-bottom: 0px">🦝 Masquerade</h1> 
<span>Biblioteca de manipulação de texto para PHP</span>
</div>

<br>

[![Static Analisys (PHP-STAN)](https://github.com/murilomagalhaes/masquerade/actions/workflows/static_analysis.yml/badge.svg)](https://github.com/murilomagalhaes/masquerade/actions/workflows/static_analysis.yml)


**Masquerade é uma biblioteca com um punhado de métodos que te ajudam a trabalhar com manipulação de texto, para que você não precise pesquisar por expressões resgulares pela milésima vez (Eu espero). Sua intenção é lidar com strings curtas, como inputs de formulários e coisas do tipo, oferecendo até métodos para máscaras/formatações.**

<hr>

## Requisitos
- `PHP: ^7.4|8.*`
- `Composer`

  
## Instalação
 `composer require murilomagalhaes/masquerade`

## Uso
- Uma vez que o pacote foi instalado, chame-o no seu arquivo desejado.
``` php
use Masquerade\Masquerade
```
- Use o método `set()` para começar a emendar filtros, máscaras, e/ou qualquer outro método disponível.
``` php
Masquerade::set("Phone: (Brazil) +55 61999995555'")
    ->only('numbers')
    ->mask('## (##) #####-####')
    ->getText(); // Retorna: "55 (61) 99999-5555"
```

## Exemplos

- Filtros
``` php

Masquerade::set("Tenho 99 problemas, mas regex não é um deles!")
    ->only('letters', 'whitespaces')
    ->getText(); // Retorna: "Tenho problemas mas regex não é um deles"

Masquerade::set("Phone: +55 00 99999-5555")
    ->only('numbers')
    ->getText(); // Retorna "5500999995555"

Masquerade::set("Assistant (to the) regional manager")
    ->between('(', ')')
    ->getText(); // Retorna "to the"

Masquerade::set("Hello, Universe")
    ->strip('Hello,')
    ->getText(); // Retorna: "Universe"

Masquerade::set("Hablo Español y Português")
    ->removeAccents()
    ->getText(); // Retorna: "Hablo Espanol y Portugues"
```

- Máscaras | Formatações
``` php
Masquerade::set("Phone: (Brazil) +55 00999995555'")
    ->only('numbers')
    ->mask('## (##) #####-####')
    ->getText(); // Retorna: "55 (00) 99999-5555"

Masquerade::set("00011122234") 
    ->only('numbers')
    ->mask('###.###.###-##')
    ->getText(); // Retorna: "000.111.222-34"
```

- Métodos customizados (Macros)
``` php
Masquerade::macro('mascaraDeTelefone', function($instance){
    return $instance->only('numbers')->mask('(##) #####-####');
});

Masquerade::set('Number: 00999995555')
    ->mascaraDeTelefone()
    ->getText(); // Retorna: "(00) 99999-5555"
```

- Getters

```php
$text = Masquerade::set('YMCA');

$text->mask('#-#-#-#');

$text->getText(); // Retorna: "Y-M-C-A"
$text->getUnmaskedText(); // Retorna: "YMCA"
```

## Available Methods

| Method Signature | Description | 
|---|---|
| `set(string $text): Masquerade` | Creates a new Masquerade instance, and defines the text string to be used by the chained methods. |
| `only(...$filter_types): Masquerade` | Removes characters NOT defined on the `$filter_types` parameter. Available filters: `'letters'`, `'numbers'` and `'whitespaces'` |
| `strip(...$characters): Masquerade` | Removes the defined characters from the text string |
| `between(string $before, string $after): Masquerade`  | Removes everything outside the defined `$before` and `$after` parameters;  |
| `removeAccents(): Masquerade`| Remove string's accents. <br>(acute\|cedil\|circ\|grave\|lig\|orn\|ring\|slash\|th\|tilde\|uml\|)
| `mask(string $pattern): Masquerade`| Applies the defined pattern to the text string |
| `format(string $pattern): Masquerade` | Alias to the mask method |
| `trim(): Masquerade` | Removes trailing and multiple spaces/tabs from the text string <br>(Method always aplied on class __toString() and getText() methods)|
| `static::macro(string $name, callable $callback): void`| Defines a macro/custom method |
| `getText(): string` | Returns the text string |
| `getOriginalText(): string` | Returns the text string before on it's original state |
| `getUnmaskedText(): string` | Returns the text string before maskking |

## Coming soon
- Punctuation filter to `only()` method.
- Add character filter exceptions to `only()` method.