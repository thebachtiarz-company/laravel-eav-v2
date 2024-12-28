<?php

namespace TheBachtiarz\EAV\Interfaces\Services;

use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Base\Interfaces\Services\ServiceInterface;
use TheBachtiarz\EAV\DTOs\Services\EavMutationInputDTO;

interface EavServiceInterface extends ServiceInterface
{
    /**
     * Create or update EAV entity
     *
     * @param EavMutationInputDTO $input
     * @return ResponseDataDTO
     */
    public function createOrUpdate(EavMutationInputDTO $input): ResponseDataDTO;
}
