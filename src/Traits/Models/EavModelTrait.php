<?php

namespace TheBachtiarz\EAV\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use TheBachtiarz\Base\Exceptions\BaseException;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\EAV\DTOs\Models\NewEntityAttribute;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Interfaces\Models\ModelInterface as EavModelInterface;
use TheBachtiarz\EAV\Interfaces\Repositories\EavRepositoryInterface;
use TheBachtiarz\EAV\Models\Eav;

/**
 * Eav Model Trait
 *
 * EAV services for models.
 * Make sure the Model is implements with "\TheBachtiarz\EAV\Interfaces\Models\ModelInterface" interface.
 * Or just simply use the extends from "\TheBachtiarz\EAV\Models\AbstractModel" abstract model.
 */
trait EavModelTrait
{
    /**
     * Model EAV(s)
     *
     * @var Collection<NewEntityAttribute|EavInterface>
     */
    protected Collection $eavAttributes;

    /**
     * Model EAV(s) temporary(es)
     *
     * @var Collection<NewEntityAttribute|EavInterface>
     */
    protected Collection $__tempEavAttributes;

    /**
     * Return current entity with eav(s)
     */
    public function withEav(bool $fresh = false): static
    {
        assert($this instanceof ModelInterface || $this instanceof Model);

        if ($this->eavs()->count() < 1 || $fresh) {
            $this->{EavModelInterface::ATTRIBUTE_EAVS} = $this->eavs(eavs: static::eavRepository()->getEntityAttributes($this));
        }

        return $this;
    }

    /**
     * Return entity eav by attribute
     *
     * @param mixed $value Update current eav with the new value.
     */
    public function eav(string $attribute, mixed $value = null): ?EavInterface
    {
        assert($this instanceof ModelInterface || $this instanceof Model);

        if (!$this?->getId()) {
            throw new BaseException(message: 'The model entity hasn\'t stored yet.', code: 404);
        }

        $eav = static::eavRepository()->getEntityAttributeValue($this, $attribute);

        if (!$value) {
            goto RESULT;
        }

        if (!$eav) {
            $eav = new Eav();
        }

        $eav = static::eavRepository()->createOrUpdate(
            model: $eav->setModelEntity($this)->setAttrName($attribute)->setAttrValue($value),
        );

        RESULT:
        return $eav;
    }

    /**
     * Retrieve model entity attribute value
     *
     * @param Collection|null $eavs Update current eav(s) in current entity
     * @return Collection<NewEntityAttribute|EavInterface>
     */
    public function eavs(?Collection $eavs = null): Collection
    {
        if ($eavs instanceof Collection) {
            $this->eavAttributes = $eavs;
        }

        return $this->eavAttributes ??= new Collection();
    }

    /**
     * Delete eav from entity
     */
    public function deleteEav(string $attribute): bool
    {
        assert($this instanceof ModelInterface || $this instanceof Model);

        if (!$this?->getId()) {
            throw new BaseException(message: 'The model entity hasn\'t stored yet.', code: 404);
        }

        $eav = static::eavRepository()->getEntityAttributeValue($this, $attribute);

        return $eav ? static::eavRepository()->deleteByPrimaryKey($eav->getId()) : false;
    }

    public function save(array $options = []): bool
    {
        assert($this instanceof ModelInterface || $this instanceof Model);

        $this->__tempEavAttributes = $this->{EavModelInterface::ATTRIBUTE_EAVS} ??= new Collection();
        unset($this->{EavModelInterface::ATTRIBUTE_EAVS});

        $process = parent::save($options);

        $this->{EavModelInterface::ATTRIBUTE_EAVS} = $this->__tempEavAttributes;

        if ($this->eavs()->count()) {
            foreach ($this->eavs()->all() as $key => $eav) {
                if (
                    $eav instanceof NewEntityAttribute ||
                    $eav instanceof EavInterface
                ) {
                    $this->eav(attribute: $eav->name, value: $eav->value);
                }
            }

            $this->withEav(fresh: true);
        }

        return $process;
    }

    /**
     * Initialize eav repository
     */
    private static function eavRepository(): EavRepositoryInterface
    {
        return app(EavRepositoryInterface::class);
    }
}
