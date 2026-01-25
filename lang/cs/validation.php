<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'alpha_dash' => 'Pole :attribute smí obsahovat pouze písmena, čísla, pomlčky a podtržítka.',
    'before' => 'Pole :attribute musí být dřívější datum než :date.',
    'digits' => 'Pole :attribute musí být přesně :digits číslic.',
    'integer' => 'Pole :attribute musí být číslo.',
    'max' => [
        'numeric' => 'Pole :attribute nesmí být vyšší než :max.',
    ],
    'min' => [
        'string' => 'Pole :attribute musí mít alespoň :min znaků.',
    ],
    'required' => 'Pole :attribute musí být vyplněné.',
    'unique' => 'Pole :attribute je již zabrané.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'birth_date' => [
            'before' => 'Datum narození musí být dřívější než dnešní datum.',
        ],
        'nickname' => [
            'unique' => 'Tato předzdívka je již zabraná. Zvolte prosím jinou.',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'first_name' => 'jméno',
        'last_name' => 'příjmení',
        'nickname' => 'přezdívka',
        'birth_date' => 'datum narození',
        'access_pin' => 'přístupový PIN',
    ],
];
