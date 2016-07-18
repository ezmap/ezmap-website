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

    'accepted'             => 'Ye huv tae accept :attribute',
    'active_url'           => 'The :attribute isny what a URL is meant tae look like.',
    'after'                => 'The :attribute hus tae be a date efter :date.',
    'alpha'                => 'The :attribute can only huv letters.',
    'alpha_dash'           => 'The :attribute can only huv letters, nummers, and dashes.',
    'alpha_num'            => 'The :attribute can only huv letters and nummers.',
    'array'                => 'The :attribute hus tae be an array.',
    'before'               => 'The :attribute hus tae be a date before :date.',
    'between'              => [
        'numeric' => 'The :attribute hus tae be atween :min and :max.',
        'file'    => 'The :attribute hus tae be atween :min and :max kilobytes.',
        'string'  => 'The :attribute hus tae hae atween :min and :max characters.',
        'array'   => 'The :attribute hus tae hae atween :min and :max hings.',
    ],
    'boolean'              => 'The :attribute hus tae be true or false.',
    'confirmed'            => 'The :attribute confirmation doesny match.',
    'date'                 => 'The :attribute isny a valid date.',
    'date_format'          => 'The :attribute doesny match the format :format.',
    'different'            => 'The :attribute and :other huv tae be different.',
    'digits'               => 'The :attribute hus tae be :digits digits.',
    'digits_between'       => 'The :attribute hus tae be between :min and :max digits.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute hus tae be a valid email address.',
    'exists'               => 'The selected :attribute isny right.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute hus tae be an image.',
    'in'                   => 'The selected :attribute isny right.',
    'in_array'             => 'The :attribute field isny in :other.',
    'integer'              => 'The :attribute hus tae be an integer.',
    'ip'                   => 'The :attribute hus tae be a valid IP address.',
    'json'                 => 'The :attribute hus tae be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute cannae be bigger than :max.',
        'file'    => 'The :attribute cannae be biger than :max kilobytes.',
        'string'  => 'The :attribute cannae be longer than :max characters.',
        'array'   => 'The :attribute cannae hae mair than :max hings.',
    ],
    'mimes'                => 'The :attribute hus tae be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute hus tae be at least :min.',
        'file'    => 'The :attribute hus tae be at least :min kilobytes.',
        'string'  => 'The :attribute hus tae be at least :min characters.',
        'array'   => 'The :attribute hus tae have at least :min hings.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute hus tae be a number.',
    'present'              => 'The :attribute field hus tae be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other hus tae match.',
    'size'                 => [
        'numeric' => 'The :attribute hus tae be :size.',
        'file'    => 'The :attribute hus tae be :size kilobytes.',
        'string'  => 'The :attribute hus tae be :size characters.',
        'array'   => 'The :attribute hus tae huv :size hings.',
    ],
    'string'               => 'The :attribute hus tae be a string.',
    'timezone'             => 'The :attribute hus tae be a valid zone.',
    'unique'               => 'The :attribute has already been nabbed.',
    'url'                  => 'The :attribute format is invalid.',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
