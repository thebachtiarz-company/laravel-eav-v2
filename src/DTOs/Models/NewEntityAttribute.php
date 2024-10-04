<?php

namespace TheBachtiarz\EAV\DTOs\Models;

class NewEntityAttribute
{
    /**
     * Add new entity attribute value
     *
     * @param string $name
     * @param mixed $value
     */
    public function __construct(
        public readonly string $name,
        public readonly mixed $value,
    ) {}
}
