<?php

namespace TheBachtiarz\EAV\Interfaces\Models;

use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;

interface EavInterface extends ModelInterface
{
    public const TABLE_NAME = 'eav_entities';

    public const ATTRIBUTE_FILLABLE = [
        self::ATTRIBUTE_ENTITY,
        self::ATTRIBUTE_ENTITY_ID,
        self::ATTRIBUTE_NAME,
        self::ATTRIBUTE_VALUE,
    ];

    public const MODEL_ENTITY = 'model';

    public const ATTRIBUTE_ENTITY = 'entity';
    public const ATTRIBUTE_ENTITY_ID = 'entity_id';
    public const ATTRIBUTE_NAME = 'name';
    public const ATTRIBUTE_VALUE = 'value';

    // ? Getter Modules

    /**
     * Get model entity
     */
    public function getModelEntity(): ModelInterface|Model;

    /**
     * Get entity name
     */
    public function getEntityName(): string|null;

    /**
     * Get entity id
     */
    public function getEntityId(): int|string|null;

    /**
     * Get attribute name
     */
    public function getAttrName(): string|null;

    /**
     * Get attribute value
     */
    public function getAttrValue(): mixed;

    // ? Setter Modules

    /**
     * Set model entity
     */
    public function setModelEntity(ModelInterface|Model $modelEntity): self;

    /**
     * Set entity name
     */
    public function setEntityName(string $entityName): self;

    /**
     * Set entity id
     */
    public function setEntityId(int|string $entityId): self;

    /**
     * Set attribute name
     */
    public function setAttrName(string $attribute): self;

    /**
     * Set attribute value
     */
    public function setAttrValue(mixed $value): self;
}
