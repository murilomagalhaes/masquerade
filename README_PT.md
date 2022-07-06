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

Masquerade::set("Tenho 998 problemas, mas regex não é um deles!")
->ignore('8')
->only('letters', 'whitespaces') // Note que o tipo 'numbers' não foi informado, então todos os numeros deveriam ter sido removidos.
->getText(), // Retorna: "Tenho 8 problemas mas regex não é um deles". O '8' foi mantido pelo método ignore()

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

## Métodos disponíveis

| Assinatura | Descrição | 
|---|---|
|`set(string $text): Masquerade` | Cria uma nova instância da classe Masquerade, e define a string do texto que será utilizada pelos métodos seguintes. |
| `only(...$filter_types): Masquerade` | Remove os caracteres de tipo NÃO informados no parametro `$filter_types`. Filtros disponíveis: `'letters'`, `'numbers'`, `'punctuation'` e `'whitespaces'` <br> Os seguintes caracteres são entendidos como punctuation (pontuação) `, . : ; ? ¿ ! ¡ - `  |
|`ignore(... $character)`| Os caracteres informados não serão removidos pelo método  `only()`. Deve ser chamado ANTES do método `only()` para funcionar |
| `strip(...$characters): Masquerade` | Remove os caracteres definidos por parametro |
| `between(string $before, string $after): Masquerade`  | Remove tudo que esteja fora das strings dos parametros parametros `$before` e `$after` |
| `removeAccents(): Masquerade`|Remove os acentos da string. <br>(acute\|cedil\|circ\|grave\|lig\|orn\|ring\|slash\|th\|tilde\|uml\|)
| `mask(string $pattern): Masquerade`| Aplica o padrão definido no texto da string |
| `format(string $pattern): Masquerade` | Apelido para o método mask() |
| `trim(): Masquerade` | Remove espaços do inicio\|final, além de espaços duplicados e tabs. <br>(Método é sempre aplicado no __toString() e getText() |
| `static::macro(string $name, callable $callback): void`| Define um método personalizado (Macro) |
| `getText(): string` | Retorna o texto da string |
| `getOriginalText(): string` | Retorna o texto da string em seu estado original |
| `getUnmaskedText(): string` | Retorna o texto da string antes de ele ter sido formatado. |