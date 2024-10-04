<?php

namespace TheBachtiarz\EAV\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Repositories\AbstractRepository;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Interfaces\Repositories\EavRepositoryInterface;

class EavRepository extends AbstractRepository implements EavRepositoryInterface
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setModelEntity(EavInterface::class);

        parent::__construct();
    }

    // ? Public Methods

    /**
     * Get model attribute(s)
     *
     * @return Collection<string>
     */
    public function getModelAttributes(ModelInterface|Model $model): Collection
    {
        $this->modelBuilder(
            modelBuilder: $this->modelEntity->query()->where(
                column: EavInterface::ATTRIBUTE_ENTITY,
                operator: '=',
                value: $model::class,
            ),
        );

        $entities = $this->modelBuilder()->get();

        return (new Collection(
            array_map(
                callback: fn(EavInterface $eav): string => $eav->getAttrName(),
                array: $entities->all(),
            ),
        ))->unique()->values();
    }

    /**
     * Get entity attribute(s)
     *
     * @return Collection<EavInterface>
     */
    public function getEntityAttributes(ModelInterface|Model $model): Collection
    {
        $this->modelBuilder(
            modelBuilder: $this->modelEntity->query()->where(
                column: EavInterface::ATTRIBUTE_ENTITY,
                operator: '=',
                value: $model::class,
            )->where(
                column: EavInterface::ATTRIBUTE_ENTITY_ID,
                operator: '=',
                value: $model->getId(),
            ),
        );

        $entities = $this->modelBuilder()->get();

        return new Collection($entities->all());
    }

    /**
     * Get entity attribute value
     */
    public function getEntityAttributeValue(ModelInterface|Model $model, string $attribute): ?EavInterface
    {
        $this->modelBuilder(
            modelBuilder: $this->modelEntity->query()->where(
                column: EavInterface::ATTRIBUTE_ENTITY,
                operator: '=',
                value: $model::class,
            )->where(
                column: EavInterface::ATTRIBUTE_ENTITY_ID,
                operator: '=',
                value: $model->getId(),
            )->where(
                column: EavInterface::ATTRIBUTE_NAME,
                operator: '=',
                value: $attribute,
            ),
        );

        return $this->modelBuilder()->latest()->first();
    }

    /**
     * Search entity(es) by attribute
     *
     * @return Collection<EavInterface>
     */
    public function searchEntitiesByAttribute(ModelInterface|Model $model, string $attribute, mixed $value): Collection
    {
        $this->modelBuilder(
            modelBuilder: $this->modelEntity->query()->where(
                column: EavInterface::ATTRIBUTE_ENTITY,
                operator: '=',
                value: $model::class,
            )->where(
                column: EavInterface::ATTRIBUTE_NAME,
                operator: '=',
                value: $attribute,
            )->where(
                column: EavInterface::ATTRIBUTE_VALUE,
                operator: 'like',
                value: "%$value%",
            ),
        );

        $entities = $this->modelBuilder()->get();

        return new Collection($entities->all());
    }

    // ? Protected Methods

    // ? Private Methods
}
