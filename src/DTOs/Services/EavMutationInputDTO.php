<?php

namespace TheBachtiarz\EAV\DTOs\Services;

use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Base\DTOs\AbstractDTO;
use TheBachtiarz\EAV\Interfaces\Models\ModelInterface;

class EavMutationInputDTO extends AbstractDTO
{
    /**
     * EAV mutation input
     *
     * @param class-string<ModelInterface|Model> $classEntity
     * @param integer $entityId
     * @param string $name
     * @param mixed $value
     * @param string|null $uuid
     */
    public function __construct(
        public readonly string $classEntity,
        public readonly int $entityId,
        public readonly string $name,
        public mixed $value = null,
        public ?string $uuid = null,
    ) {}
}
