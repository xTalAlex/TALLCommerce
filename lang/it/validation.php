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

    'accepted' => ':attribute deve essere accettato.',
    'accepted_if' => ':attribute deve essere accettato quando :other è :value.',
    'active_url' => ':attribute non è un URL valido.',
    'after' => ':attribute deve essere una data successiva a :date.',
    'after_or_equal' => ':attribute deve essere una data uguale o successiva a :date.',
    'alpha' => ':attribute può contenere solo lettere.',
    'alpha_dash' => ':attribute può contenere solo lettere, numeri, trattini and underscore.',
    'alpha_num' => ':attribute può contenere solo lettere e numeri.',
    'array' => ':attribute deve essere un array.',
    'before' => ':attribute deve essere una data precedente a :date.',
    'before_or_equal' => ':attribute deve essere una data uguale o precedente a :date.',
    'between' => [
        'array' => ':attribute deve avere tra :min e :max oggetti.',
        'file' => ':attribute può pesare tra :min e :max kilobytes.',
        'numeric' => ':attribute deve essere tra :min e :max.',
        'string' => ':attribute deve avere tra :min e :max caratteri.',
    ],
    'boolean' => ':attribute deve essere vero o falso.',
    'confirmed' => 'Conferma :attribute non corrisponde.',
    'current_password' => 'La password non è corretta.',
    'date' => ':attribute non è una data valida.',
    'date_equals' => ':attribute deve essere una data uguale a :date.',
    'date_format' => ':attribute non corrisponde al formato :format.',
    'declined' => ':attribute deve essere rifiutato.',
    'declined_if' => ':attribute deve essere rifiutato quando :other è :value.',
    'different' => 'I campi :attribute e :other devono essere diversi.',
    'digits' => ':attribute deve avere :digits cifre.',
    'digits_between' => ':attribute deve avere tra :min e :max cifre.',
    'dimensions' => ':attribute ha dimensioni di immagine non valide.',
    'distinct' => 'Il valore di :attribute è un duplicato.',
    'email' => ':attribute deve essere una email valida.',
    'ends_with' => ':attribute deve finire con: :values.',
    'enum' => 'Il valore scelto per :attribute non è valido.',
    'exists' => 'Il valore scelto per :attribute non è valido.',
    'file' => ':attribute deve essere un file.',
    'filled' => 'Il campo :attribute deve avere un valore.',
    'gt' => [
        'array' => ':attribute deve avere più di :value oggetti.',
        'file' => ':attribute deve pesare più di :value kilobytes.',
        'numeric' => ':attribute deve essere maggiore di :value.',
        'string' => ':attribute deve avere più di :value caratteri.',
    ],
    'gte' => [
        'array' => ':attribute deve avere almeno :value oggetti.',
        'file' => ':attribute deve pesare almeno :value kilobytes.',
        'numeric' => ':attribute deve essere maggiore o uguale a :value.',
        'string' => ':attribute deve avere almeno :value caratteri.',
    ],
    'image' => ':attribute deve essere una immagine.',
    'in' => 'Il valore scelto per :attribute non è valido.',
    'in_array' => 'Il valore del campo :attribute non è tra quelli di :other.',
    'integer' => ':attribute deve essere un intero.',
    'ip' => ':attribute deve essere un indirizzo IP valido.',
    'ipv4' => ':attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => ':attribute deve essere un indirizzo IPv6 valido.',
    'json' => ':attribute deve essere una stringa JSON.',
    'lt' => [
        'array' => ':attribute deve avere meno di :value oggetti.',
        'file' => ':attribute deve pesare meno di :value kilobytes.',
        'numeric' => ':attribute deve essere minore di :value.',
        'string' => ':attribute deve avere meno di :value caratteri.',
    ],
    'lte' => [
        'array' => ':attribute non può avere più di :value oggetti.',
        'file' => ':attribute non può pesare più di :value kilobytes.',
        'numeric' => ':attribute deve essere minore o uguale a :value.',
        'string' => ':attribute non può avere più di :value caratteri.',
    ],
    'mac_address' => ':attribute deve essere un indirizzo MAC valido.',
    'max' => [
        'array' => ':attribute non può avere più di :max oggetti.',
        'file' => ':attribute non può pesare più di :max kilobytes.',
        'numeric' => ':attribute non può essere maggiore di :max.',
        'string' => ':attribute non può avere più di :max caratteri.',
    ],
    'mimes' => ':attribute deve essere un file di tipo: :values.',
    'mimetypes' => ':attribute deve essere un file di tip: :values.',
    'min' => [
        'array' => ':attribute deve avere almeno :min oggetti.',
        'file' => ':attribute deve pesare almeno :min kilobytes.',
        'numeric' => ':attribute deve essere almeno :min.',
        'string' => ':attribute deve avere almeno :min caratteri.',
    ],
    'multiple_of' => ':attribute deve essere multiplo di :value.',
    'not_in' => 'Il valore scelto per :attribute non è valido.',
    'not_regex' => 'Il formato per :attribute non è valido.',
    'numeric' => ':attribute deve essere un numero.',
    'password' => [
        'letters' => ':attribute deve contenere almeno una lettera.',
        'mixed' => ':attribute deve contenere almeno una lettera maiuscola ed una minuscola.',
        'numbers' => ':attribute deve contenere almeno un numero.',
        'symbols' => ':attribute deve contenere almeno un simbolo.',
        'uncompromised' => 'Il valore scelto per :attribute non è sicuro.',
    ],
    'present' => 'Il campo :attribute deve essere presente.',
    'prohibited' => 'Il campo :attribute non è permesso.',
    'prohibited_if' => 'Il campo :attribute non è permesso quando :other è :value.',
    'prohibited_unless' => 'Il campo :attribute non è permesso tranne quando :other è in :values.',
    'prohibits' => 'Il campo :attribute non permette a :other di essere presente.',
    'regex' => 'Il formato per :attribute non è valido.',
    'required' => 'Il campo :attribute è obbligatorio.',
    'required_array_keys' => 'Il campo :attribute deve contenere valori per: :values.',
    'required_if' => 'Il campo :attribute è obbligatorio quando :other è :value.',
    'required_unless' => 'Il campo :attribute è obbligatorio tranne quando :other è in :values.',
    'required_with' => 'Il campo :attribute è obbligatorio quando :values è presente.',
    'required_with_all' => 'Il campo :attribute è obbligatorio quando :values sono presenti.',
    'required_without' => 'Il campo :attribute è obbligatorio quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è obbligatorio quando nessuno tra :values è presente.',
    'same' => ':attribute e :other devono coincidere.',
    'size' => [
        'array' => ':attribute deve contenere :size oggetti.',
        'file' => ':attribute deve pesare :size kilobytes.',
        'numeric' => ':attribute deve essere :size.',
        'string' => ':attribute deve avere :size caratteri.',
    ],
    'starts_with' => ':attribute deve cominciare con: :values.',
    'string' => ':attribute deve essere una stringa.',
    'timezone' => ':attribute deve essere un fuso orario valido.',
    'unique' => ':attribute esiste già.',
    'uploaded' => ':attribute non è stato caricato.',
    'url' => ':attribute deve essere un URL valido.',
    'uuid' => ':attribute deve essere un UUID valido.',

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

    'attributes' => [
        'full_name' => 'Nome Completo',
        'company' => 'Ragione Sociale',
        'address' => 'Indirizzo',
        'city' => 'Città',
        'province' => 'Provincia',
        'country_region' => 'Paese',
        'postal_code' => 'Codice Postale',
        'vat' => 'Partita IVA',
        'fiscal_code' => 'Codice Fiscale',
        'phone' => 'Numero di telefono',
        'phot' => 'L\'immagine'
    ],

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
        'billing_full_name' => [
            'empty' => 'vuoto'
        ],
        'full_name' => [
            'empty' => 'vuoto'
        ],
    ],
];
