# API Documentation

Eloquent model that use the
```php
Anacreation\traits\TranslatableTrait
```

can use the following APIs

## Create and update translations

#### createModelWithTranslations( array $attributes = [], array $content): Model
the $content has a predefine format

    [
        "language_code_1"=>[
            "attribute_1" => "value 1",
            "attribute_2" => "value 2",
            "attribute_3" => "value 3",
        ],

        "language_code_2"=>[
            "attribute_1" => "value 4",
            "attribute_2" => "value 5",
            "attribute_3" => "value 6",
        ]
    ]

it create a model instance and save the content

#### updateTranslations(array $content): void

No matter for create new language transaltion or update existing language. Simple call this funciton.
The $content structure is same as above.

## Retrieve translation

if you have a model with translation as above.

then you can
```php
$mode->attribute_1;
```
this will automatically fetch the translation base on your current locale setting.
```php
app()->getLocale();
```
### Fallback
Then defalut fallback is set to false, no fallback if content is null.

The fall back system is very simple. if you have set the config fallback_locale **and** set the eloquent model fallback to true
```php
model->fallback = true;
```

then if the translation for particular attribute is null. It will try to get the translation for fallback locale.

### Retrieve all translation
```php
$translation_array = model->translatables;
```
this will return a array as above.
The translation array has structure as below:

    [
        "language_code_1"=>[
            "attribute_1" => "value 1",
            "attribute_2" => "value 2",
        ],

        "language_code_2"=>[
            "attribute_1" => "value 3",
            "attribute_2" => "value 4",
        ]
    ]

## Delete Translation

#### deleteTranslatableAttribute( string $key, string $locale = null ): void
This will delete specfic attribute for all or speficied transaltion.
If the original translation as below:

    [
        "language_code_1"=>[
            "attribute_1" => "value 1",
            "attribute_2" => "value 2",
        ],

        "language_code_2"=>[
            "attribute_1" => "value 3",
            "attribute_2" => "value 4",
        ]
    ]
We call
```php
    $model->deleteTranslatableAttribute("attribute_2");
```
the translation will become

    [
        "language_code_1"=>[
            "attribute_1" => "value 1",
        ],

        "language_code_2"=>[
            "attribute_1" => "value 3",
        ]
    ]

if we call
```php
    $model->deleteTranslatableAttribute("attribute_2", "language_code_2");
```
the result will as below

    [
        "language_code_1"=>[
            "attribute_1" => "value 1",
            "attribute_2" => "value 2",
        ],

        "language_code_2"=>[
            "attribute_1" => "value 3",
        ]
    ]


#### deleteTranslatableWithLocale(string $locale ): void

This will remove all translation with specified locale.
if we call
```php
    $model->deleteTranslatableWithLocale("language_code_2");
```
the result will as below

    [
        "language_code_1"=>[
            "attribute_1" => "value 1",
            "attribute_2" => "value 2",
        ]
    ]

