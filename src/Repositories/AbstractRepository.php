<?php

namespace TheBachtiarz\EAV\Repositories;

use TheBachtiarz\Base\Repositories\AbstractRepository as BaseAbstractRepository;
use TheBachtiarz\EAV\Interfaces\Repositories\RepositoryInterface;
use TheBachtiarz\EAV\Traits\Repositories\EavRepositoryTrait;

abstract class AbstractRepository extends BaseAbstractRepository implements RepositoryInterface
{
    use EavRepositoryTrait;
}
