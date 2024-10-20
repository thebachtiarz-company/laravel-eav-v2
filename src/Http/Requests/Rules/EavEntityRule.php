<?php

namespace TheBachtiarz\EAV\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;

class EavEntityRule extends AbstractRule
{
    public const ENTITY = 'entity';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::ENTITY => [
                'required',
                'regex:/^[a-zA-Z\\\\]+$/',
            ],
        ];
    }
}
