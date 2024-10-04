<?php

namespace TheBachtiarz\EAV\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;

class EavNameRule extends AbstractRule
{
    public const NAME = 'name';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::NAME => [
                'required',
                'string',
                'alpha_dash:ascii',
            ],
        ];
    }
}
