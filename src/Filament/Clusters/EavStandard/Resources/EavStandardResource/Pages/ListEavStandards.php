<?php

namespace TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource;

class ListEavStandards extends ListRecords
{
    protected static string $resource = EavStandardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add New EAV')->icon('heroicon-c-plus')->iconPosition(IconPosition::Before),
        ];
    }
}
