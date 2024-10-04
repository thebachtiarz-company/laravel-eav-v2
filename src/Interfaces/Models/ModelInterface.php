<?php

namespace TheBachtiarz\EAV\Interfaces\Models;

use Illuminate\Support\Collection;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface as BaseModelInterface;
use TheBachtiarz\EAV\DTOs\Models\NewEntityAttribute;

interface ModelInterface extends BaseModelInterface
{
    /**
     * Entity Attribute Value
     */
    public const string ATTRIBUTE_EAVS = 'eav_attributes';

    /**
     * Return current entity with eav(s)
     */
    public function withEav(bool $fresh = false): static;

    /**
     * Return entity eav by attribute
     *
     * @param mixed $value Update current eav with the new value.
     */
    public function eav(string $attribute, mixed $value = null): ?EavInterface;

    /**
     * Retrieve model entity attribute value
     *
     * @param Collection|null $eavs Update current eav(s) in current entity
     * @return Collection<NewEntityAttribute|EavInterface>
     */
    public function eavs(?Collection $eavs = null): Collection;

    /**
     * Delete eav from entity
     */
    public function deleteEav(string $attribute): bool;
}
