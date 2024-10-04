<?php

namespace TheBachtiarz\EAV\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;

class EavValueRule extends AbstractRule
{
    public const VALUE = 'value';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::VALUE => [
                'required',
                'string',
            ],
        ];
    }
}
