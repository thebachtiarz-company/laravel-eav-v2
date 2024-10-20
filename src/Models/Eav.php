<?php

namespace TheBachtiarz\EAV\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Base\Casts\Serialize;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Models\AbstractModel;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Models\Factories\EavFactory;

class Eav extends AbstractModel implements EavInterface
{
    use HasUlids;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(self::TABLE_NAME);
        $this->fillable(self::ATTRIBUTE_FILLABLE);
        $this->setHidden([
            $this->getPrimaryKeyAttribute(),
            self::ATTRIBUTE_ENTITY,
            self::ATTRIBUTE_ENTITY_ID,
        ]);

        $this->modelFactory = EavFactory::class;

        parent::__construct($attributes);
    }

    // ? Public Methods

    public function model(): Attribute
    {
        return new Attribute(
            get: fn(): ?ModelInterface => app($this->getEntityName())::withTrashed()->find($this->getEntityId()),
        );
    }

    #[\Override]
    public function save(array $options = []): bool
    {
        $model = $this->{self::MODEL_ENTITY};
        unset($this->{self::MODEL_ENTITY});

        $process = parent::save($options);

        $this->{self::MODEL_ENTITY} = $model;

        return $process;
    }

    // ? Protected Methods

    #[\Override]
    protected function casts(): array
    {
        return [
            self::ATTRIBUTE_VALUE => Serialize::class,
        ];
    }

    // ? Getter Modules

    /**
     * Get model entity
     */
    public function getModelEntity(): ModelInterface|Model|null
    {
        return $this->{self::MODEL_ENTITY};
    }

    /**
     * Get entity name
     */
    public function getEntityName(): string|null
    {
        return $this->{self::ATTRIBUTE_ENTITY};
    }

    /**
     * Get entity id
     */
    public function getEntityId(): int|string|null
    {
        return $this->{self::ATTRIBUTE_ENTITY_ID};
    }

    /**
     * Get attribute name
     */
    public function getAttrName(): string|null
    {
        return $this->{self::ATTRIBUTE_NAME};
    }

    /**
     * Get attribute value
     */
    public function getAttrValue(): mixed
    {
        return $this->{self::ATTRIBUTE_VALUE};
    }

    // ? Setter Modules

    /**
     * Set model entity
     */
    public function setModelEntity(ModelInterface|Model $modelEntity): self
    {
        $this->{self::MODEL_ENTITY} = $modelEntity;
        $this->setEntityName($modelEntity::class);
        $this->setEntityId($modelEntity->getId());

        return $this;
    }

    /**
     * Set entity name
     */
    public function setEntityName(string $entityName): self
    {
        $this->{self::ATTRIBUTE_ENTITY} = $entityName;

        return $this;
    }

    /**
     * Set entity id
     */
    public function setEntityId(int|string $entityId): self
    {
        $this->{self::ATTRIBUTE_ENTITY_ID} = $entityId;

        return $this;
    }

    /**
     * Set attribute name
     */
    public function setAttrName(string $attribute): self
    {
        $this->{self::ATTRIBUTE_NAME} = $attribute;

        return $this;
    }

    /**
     * Set attribute value
     */
    public function setAttrValue(mixed $value): self
    {
        $this->{self::ATTRIBUTE_VALUE} = $value;

        return $this;
    }
}
