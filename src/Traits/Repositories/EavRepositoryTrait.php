<?php

namespace TheBachtiarz\EAV\Traits\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;

/**
 * Eav Repository Trait
 *
 * EAV services for repositories.
 * Make sure the Repository is implements with "\TheBachtiarz\EAV\Interfaces\Repositories\RepositoryInterface" interface.
 * Or just simply use the extends from "\TheBachtiarz\EAV\Repositories\AbstractRepository" abstract repository.
 */
trait EavRepositoryTrait
{
    #[\Override]
    public function getByPrimaryKey(int|string $primaryKey): ModelInterface|Model|null
    {
        $result = parent::getByPrimaryKey(primaryKey: $primaryKey);

        return $result->withEav();
    }

    #[\Override]
    public function collection(): Collection
    {
        return new Collection([...array_map(
            callback: fn(ModelInterface|Model $model): ModelInterface|Model => $model->withEav(),
            array: parent::collection()->all(),
        )]);
    }

    #[\Override]
    protected function createFromModel(ModelInterface|Model $model): ModelInterface|Model
    {
        $model->save(options: parent::prepareCreate(model: $model));

        return $model;
    }
}
