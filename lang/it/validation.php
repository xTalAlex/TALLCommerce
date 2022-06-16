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

    'accepted' => 'Il campo :attribute deve essere accettato.',
    'accepted_if' => 'Il campo :attribute deve essere accettato quando :other è :value.',
    'active_url' => 'Il campo :attribute non è un URL valido.',
    'after' => 'Il campo :attribute deve essere una data successiva a :date.',
    'after_or_equal' => 'Il campo :attribute deve essere una data uguale o successiva a :date.',
    'alpha' => 'Il campo :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il campo :attribute può contenere solo lettere, numeri, trattini and underscore.',
    'alpha_num' => 'Il campo :attribute può contenere solo lettere e numeri.',
    'array' => 'Il campo :attribute deve essere un array.',
    'before' => 'Il campo :attribute deve essere una data precedente a :date.',
    'before_or_equal' => 'Il campo :attribute deve essere una data uguale o precedente a :date.',
    'between' => [
        'array' => 'Il campo :attribute deve avere tra :min e :max oggetti.',
        'file' => 'Il campo :attribute può pesare tra :min e :max kilobytes.',
        'numeric' => 'Il campo :attribute deve essere tra :min e :max.',
        'string' => 'Il campo :attribute deve avere tra :min e :max caratteri.',
    ],
    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'confirmed' => 'Il campo di conferma :attribute non corrisponde.',
    'current_password' => 'La password non è corretta.',
    'date' => 'Il campo :attribute non è una data valida.',
    'date_equals' => 'Il campo :attribute deve essere una data uguale a :date.',
    'date_format' => 'Il campo :attribute non corrisponde al formato :format.',
    'declined' => 'Il campo :attribute deve essere rifiutato.',
    'declined_if' => 'Il campo :attribute deve essere rifiutato quando :other è :value.',
    'different' => 'I campi :attribute e :other devono essere diversi.',
    'digits' => 'Il campo :attribute deve avere :digits cifre.',
    'digits_between' => 'Il campo :attribute deve avere tra :min e :max cifre.',
    'dimensions' => 'Il campo :attribute ha dimensioni di immagine non valide.',
    'distinct' => 'Il valore del campo :attribute è un duplicato.',
    'email' => 'Il campo :attribute deve essere una email valida.',
    'ends_with' => 'Il campo :attribute deve finire con: :values.',
    'enum' => 'Il campo :attribute is invalid.',
    'exists' => 'Il valore scelto per :attribute non è valido.',
    'file' => 'Il campo :attribute deve essere un file.',
    'filled' => 'Il campo :attribute deve avere un valore.',
    'gt' => [
        'array' => 'Il campo :attribute deve avere più di :value oggetti.',
        'file' => 'Il campo :attribute deve pesare più di :value kilobytes.',
        'numeric' => 'Il campo :attribute deve essere maggiore di :value.',
        'string' => 'Il campo :attribute deve avere più di :value caratteri.',
    ],
    'gte' => [
        'array' => 'Il campo :attribute deve avere almeno :value oggetti.',
        'file' => 'Il campo :attribute deve pesare almeno :value kilobytes.',
        'numeric' => 'Il campo :attribute deve essere maggiore o uguale a :value.',
        'string' => 'Il campo :attribute deve avere almeno :value caratteri.',
    ],
    'image' => 'Il campo :attribute deve essere una immagine.',
    'in' => 'Il valore scelto per :attribute non è valido.',
    'in_array' => 'Il valore del campo :attribute non è tra quelli di :other.',
    'integer' => 'Il campo :attribute deve essere un intero.',
    'ip' => 'Il campo :attribute deve essere un indirizzo IP valido.',
    'ipv4' => 'Il campo :attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => 'Il campo :attribute deve essere un indirizzo IPv6 valido.',
    'json' => 'Il campo :attribute deve essere una stringa JSON.',
    'lt' => [
        'array' => 'Il campo :attribute deve avere meno di :value oggetti.',
        'file' => 'Il campo :attribute deve pesare meno di :value kilobytes.',
        'numeric' => 'Il campo :attribute deve essere minore di :value.',
        'string' => 'Il campo :attribute deve avere meno di :value caratteri.',
    ],
    'lte' => [
        'array' => 'Il campo :attribute non può avere più di :value oggetti.',
        'file' => 'Il campo :attribute non può pesare più di :value kilobytes.',
        'numeric' => 'Il campo :attribute deve essere minore o uguale a :value.',
        'string' => 'Il campo :attribute non può avere più di :value caratteri.',
    ],
    'mac_address' => 'Il campo :attribute deve essere un indirizzo MAC valido.',
    'max' => [
        'array' => 'Il campo :attribute non può avere più di :value oggetti.',
        'file' => 'Il campo :attribute non può pesare più di :value kilobytes.',
        'numeric' => 'Il campo :attribute non può essere maggiore di :value.',
        'string' => 'Il campo :attribute non può avere più di :value caratteri.',
    ],
    'mimes' => 'Il campo :attribute deve essere un file di tipo: :values.',
    'mimetypes' => 'Il campo :attributedeve essere un file di tip: :values.',
    'min' => [
        'array' => 'Il campo :attribute deve avere almeno :min oggetti.',
        'file' => 'Il campo :attribute deve pesare almeno :min kilobytes.',
        'numeric' => 'Il campo :attribute deve essere almeno :min.',
        'string' => 'Il campo :attribute deve avere almeno :min caratteri.',
    ],
    'multiple_of' => 'Il campo :attribute deve essere multiplo di :value.',
    'not_in' => 'Il valore scelto per :attribute non è valido.',
    'not_regex' => 'Il formato del campo :attribute non è valido.',
    'numeric' => 'Il campo :attribute deve essere un numero.',
    'password' => [
        'letters' => 'Il campo :attribute deve contenere almeno una lettera.',
        'mixed' => 'Il campo :attribute deve contenere almeno una lettera maiuscola ed una minuscola.',
        'numbers' => 'Il campo :attribute deve contenere almeno un numero.',
        'symbols' => 'Il campo :attribute deve contenere almeno un simbolo.',
        'uncompromised' => 'Il valore scelto per :attribute non è sicuro.',
    ],
    'present' => 'Il campo :attribute deve essere presente.',
    'prohibited' => 'Il campo :attribute non è permesso.',
    'prohibited_if' => 'Il campo :attribute non è permesso quando :other è :value.',
    'prohibited_unless' => 'Il campo :attribute non è permesso tranne quando :other è in :values.',
    'prohibits' => 'Il campo :attribute non permette a :other di essere presente.',
    'regex' => 'Il formato del campo :attribute non è valid.',
    'required' => 'Il campo :attribute è obbligatorio.',
    'required_array_keys' => 'Il campo :attribute deve contenere valori per: :values.',
    'required_if' => 'Il campo :attribute è obbligatorio quando :other è :value.',
    'required_unless' => 'Il campo :attribute è obbligatorio tranne quando :other è in :values.',
    'required_with' => 'Il campo :attribute è obbligatorio quando :values è presente.',
    'required_with_all' => 'Il campo :attribute è obbligatorio quando :values sono presenti.',
    'required_without' => 'Il campo :attribute è obbligatorio quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è obbligatorio quando nessuno tra :values è presente.',
    'same' => 'Il campo :attribute e :other devono coincidere.',
    'size' => [
        'array' => 'Il campo :attribute deve contenere :size oggetti.',
        'file' => 'Il campo :attribute deve pesare :size kilobytes.',
        'numeric' => 'Il campo :attribute deve essere :size.',
        'string' => 'Il campo :attribute deve avere :size caratteri.',
    ],
    'starts_with' => 'Il campo :attribute deve cominciare con: :values.',
    'string' => 'Il campo :attribute deve essere una stringa.',
    'timezone' => 'Il campo :attribute deve essere un fuso orario valido.',
    'unique' => 'Il campo :attribute esiste già.',
    'uploaded' => 'Il campo :attribute non è stato caricato.',
    'url' => 'Il campo :attribute deve essere un URL valido.',
    'uuid' => 'Il campo :attribute deve essere un UUID valido.',

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
        'attribute-name' => [
            'rule-name' => 'custom-message',
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

    'attributes' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Values
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our value placeholder
    | with something more reader friendly such as "credit card" instead
    | of "cc". This simply helps us make our message more expressive.
    |
    */

    'values' => [
        'attribute-name' => [
            'value' => 'custom-value'
        ],
    ],
];
