<?php

namespace TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\EAV\DTOs\Services\EavMutationInputDTO;
use TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Interfaces\Services\EavServiceInterface;

class EditEavStandard extends EditRecord
{
    protected static string $resource = EavStandardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Delete')->icon('heroicon-s-trash')->iconPosition(IconPosition::Before),
        ];
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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $entity = $this->record;
        assert($entity instanceof EavInterface);

        $process = app(EavServiceInterface::class)->createOrUpdate(new EavMutationInputDTO(
            classEntity: $data[EavInterface::ATTRIBUTE_ENTITY],
            entityId: $data[EavInterface::ATTRIBUTE_ENTITY_ID],
            name: $data[EavInterface::ATTRIBUTE_NAME],
            value: $data[EavInterface::ATTRIBUTE_VALUE],
            uuid: $entity->getId(),
        ));

        if (!$process->condition->toBoolean()) {
            Notification::make()
                ->danger()
                ->title($process->message)
                ->send();

            throw new Halt();
        }

        return $process->model;
    }
}
