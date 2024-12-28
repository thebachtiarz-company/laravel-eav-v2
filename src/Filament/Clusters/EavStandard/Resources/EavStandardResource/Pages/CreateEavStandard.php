<?php

namespace TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\EAV\DTOs\Services\EavMutationInputDTO;
use TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;
use TheBachtiarz\EAV\Interfaces\Services\EavServiceInterface;

class CreateEavStandard extends CreateRecord
{
    protected static string $resource = EavStandardResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $process = app(EavServiceInterface::class)->createOrUpdate(new EavMutationInputDTO(
            classEntity: $data[EavInterface::ATTRIBUTE_ENTITY],
            entityId: $data[EavInterface::ATTRIBUTE_ENTITY_ID],
            name: $data[EavInterface::ATTRIBUTE_NAME],
            value: $data[EavInterface::ATTRIBUTE_VALUE],
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
