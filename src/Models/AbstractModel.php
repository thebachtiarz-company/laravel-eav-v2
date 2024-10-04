<?php

namespace TheBachtiarz\EAV\Models;

use TheBachtiarz\Base\Models\AbstractModel as BaseAbstractModel;
use TheBachtiarz\EAV\Interfaces\Models\ModelInterface;
use TheBachtiarz\EAV\Traits\Models\EavModelTrait;

abstract class AbstractModel extends BaseAbstractModel implements ModelInterface
{
    use EavModelTrait;
}
