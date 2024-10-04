<?php

namespace TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Base\Enums\Services\ResponseConditionEnum;
use TheBachtiarz\Base\Enums\Services\ResponseHttpCodeEnum;
use TheBachtiarz\Base\Enums\Services\ResponseStatusEnum;
use TheBachtiarz\Base\Exceptions\BaseException;
use TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Interfaces\Repositories\EavRepositoryInterface;
use TheBachtiarz\EAV\Models\Eav;

class CreateEavStandard extends CreateRecord
{
    protected static string $resource = EavStandardResource::class;

    protected EavRepositoryInterface $eavRepository;

    protected ResponseDataDTO $processResponse;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eavRepository = app(EavRepositoryInterface::class);
        $this->processResponse = new ResponseDataDTO();
    }

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        if (!class_exists($data[EavInterface::ATTRIBUTE_ENTITY])) {
            Notification::make()
                ->danger()
                ->title('Entity type not found!')
                ->send();

            throw new BaseException(message: 'Entity type not found!', code: 400);
        }
    }

    protected function handleRecordCreation(array $data): Model
    {
        $prepare = new Eav();
        $prepare->setModelEntity(app($data[EavInterface::ATTRIBUTE_ENTITY])::find($data[EavInterface::ATTRIBUTE_ENTITY_ID]));
        $prepare->setAttrName($data[EavInterface::ATTRIBUTE_NAME]);
        $prepare->setAttrValue($data[EavInterface::ATTRIBUTE_VALUE]);

        $entity = $this->eavRepository->createOrUpdate(model: $prepare);

        $this->processResponse = new ResponseDataDTO(
            condition: ResponseConditionEnum::TRUE,
            status: ResponseStatusEnum::SUCCESS,
            httpCode: ResponseHttpCodeEnum::CREATED,
            message: 'Eav created',
            data: $entity->simpleListMap(),
        );

        if (!$this->processResponse->condition->value) {
            return new Eav($data);
        }

        assert($entity instanceof Model);

        return $entity;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->{$this->processResponse->condition->value ? 'success' : 'danger'}()
            ->title(sprintf('%s create new entity', $this->processResponse->condition->value ? 'Successfully' : 'Failed to'))
            ->body($this->processResponse->condition->value ? null : $this->processResponse->message)
            ->send();
    }
}
