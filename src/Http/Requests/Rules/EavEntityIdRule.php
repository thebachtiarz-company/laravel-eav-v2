<?php

namespace TheBachtiarz\EAV\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;

class EavEntityIdRule extends AbstractRule
{
    public const ENTITY_ID = 'entity_id';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::ENTITY_ID => [
                'required',
                'alpha_num',
            ],
        ];
    }
}
