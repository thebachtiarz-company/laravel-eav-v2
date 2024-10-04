<?php

namespace TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Base\Enums\Services\ResponseConditionEnum;
use TheBachtiarz\Base\Enums\Services\ResponseHttpCodeEnum;
use TheBachtiarz\Base\Enums\Services\ResponseStatusEnum;
use TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Interfaces\Repositories\EavRepositoryInterface;

class EditEavStandard extends EditRecord
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Delete')->icon('heroicon-s-trash')->iconPosition(IconPosition::Before),
        ];
    }

    protected function beforeFill(): void
    {
        // Runs before the form fields are populated from the database.
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $entity = $this->record;
        assert($entity instanceof EavInterface);

        $model = $entity->getModelEntity();

        return [
            EavInterface::ATTRIBUTE_ENTITY => $model::class,
            EavInterface::ATTRIBUTE_ENTITY_ID => $model->getId(),
            EavInterface::ATTRIBUTE_NAME => $entity->getAttrName(),
            EavInterface::ATTRIBUTE_VALUE => $entity->getAttrValue(),
        ];
    }

    protected function afterFill(): void
    {
        // Runs after the form fields are populated from the database.
    }

    protected function beforeValidate(): void
    {
        // Runs before the form fields are validated when the form is saved.
    }

    protected function afterValidate(): void
    {
        // Runs after the form fields are validated when the form is saved.
    }

    protected function beforeSave(): void
    {
        // Runs before the form fields are saved to the database.
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $entity = $this->record;
        assert($entity instanceof EavInterface);

        $entity->setEntityName($data[EavInterface::ATTRIBUTE_ENTITY])->setEntityId($data[EavInterface::ATTRIBUTE_ENTITY_ID]);
        $entity->setAttrName($data[EavInterface::ATTRIBUTE_NAME])->setAttrValue($data[EavInterface::ATTRIBUTE_VALUE]);

        $entity = $this->eavRepository->createOrUpdate(model: $entity);

        $this->processResponse = new ResponseDataDTO(
            condition: ResponseConditionEnum::TRUE,
            status: ResponseStatusEnum::SUCCESS,
            httpCode: ResponseHttpCodeEnum::CREATED,
            message: 'Eav created',
            data: $entity->simpleListMap(),
        );

        if (!$this->processResponse->condition->value) {
            return $record;
        }

        assert($entity instanceof Model);

        return $entity;
    }

    protected function afterSave(): void
    {
        // Runs after the form fields are saved to the database.
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->{$this->processResponse->condition->value ? 'success' : 'danger'}()
            ->title($this->processResponse->condition->value ? 'Changes has been saved!' : 'Failed to update config!')
            ->body($this->processResponse->condition->value ? null : $this->processResponse->message)
            ->send();
    }
}
