<?php

namespace TheBachtiarz\EAV\Services;

use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Base\Enums\Services\ResponseConditionEnum;
use TheBachtiarz\Base\Enums\Services\ResponseHttpCodeEnum;
use TheBachtiarz\Base\Enums\Services\ResponseStatusEnum;
use TheBachtiarz\Base\Services\AbstractService;
use TheBachtiarz\EAV\DTOs\Services\EavMutationInputDTO;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Interfaces\Repositories\EavRepositoryInterface;
use TheBachtiarz\EAV\Interfaces\Services\EavServiceInterface;

class EavService extends AbstractService implements EavServiceInterface
{
    public function __construct(
        protected EavRepositoryInterface $eavRepository,
    ) {
        parent::__construct(
            response: new ResponseDataDTO(),
        );
    }

    public function createOrUpdate(EavMutationInputDTO $input): ResponseDataDTO
    {
        try {
            throw_if(!class_exists($input->classEntity), 'Exception', 'Entity class not found!');

            $entity = app(EavInterface::class)
                ->setModelEntity(app($input->classEntity)::find($input->entityId));

            if ($input->uuid) {
                $entity = $this->eavRepository->getByPrimaryKey($input->uuid);
            }

            assert($entity instanceof EavInterface);

            $entity
                ->setAttrName($input->name)
                ->setAttrValue($input->value);

            $process = $this->eavRepository->createOrUpdate($entity);

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::CREATED,
                message: 'Successfully create or update eav entity',
                data: $process->simpleListMap(),
                model: $process,
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }
}
