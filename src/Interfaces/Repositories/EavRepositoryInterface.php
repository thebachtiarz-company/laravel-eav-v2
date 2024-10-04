<?php

namespace TheBachtiarz\EAV\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Interfaces\Repositories\RepositoryInterface;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;

interface EavRepositoryInterface extends RepositoryInterface
{
    /**
     * Get model attribute(s)
     *
     * @return Collection<string>
     */
    public function getModelAttributes(ModelInterface|Model $model): Collection;

    /**
     * Get entity attribute(s)
     *
     * @return Collection<EavInterface>
     */
    public function getEntityAttributes(ModelInterface|Model $model): Collection;

    /**
     * Get entity attribute value
     */
    public function getEntityAttributeValue(ModelInterface|Model $model, string $attribute): ?EavInterface;

    /**
     * Search entity(es) by attribute
     *
     * @return Collection<EavInterface>
     */
    public function searchEntitiesByAttribute(ModelInterface|Model $model, string $attribute, mixed $value): Collection;
}
