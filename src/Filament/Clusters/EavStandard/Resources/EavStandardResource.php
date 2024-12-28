<?php

namespace TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Admin\Traits\Filament\Resources\HasAuthorizedResource;
use TheBachtiarz\EAV\Filament\Clusters\EavStandard\Resources\EavStandardResource\Pages;
use TheBachtiarz\EAV\Http\Requests\Rules\EavEntityIdRule;
use TheBachtiarz\EAV\Http\Requests\Rules\EavEntityRule;
use TheBachtiarz\EAV\Http\Requests\Rules\EavNameRule;
use TheBachtiarz\EAV\Http\Requests\Rules\EavValueRule;
use TheBachtiarz\EAV\Models\Eav;
use TheBachtiarz\EAV\Interfaces\Models\EavInterface;

class EavStandardResource extends Resource
{
    use HasAuthorizedResource;

    protected static ?string $modelLabel = 'Eav Entity';

    protected static ?int $navigationSort = 40;

    protected static ?string $slug = 'eav-entity';

    protected static ?string $model = Eav::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Group::make()->schema([
                        Forms\Components\TextInput::make(EavInterface::ATTRIBUTE_ENTITY)->label('Entity Type')->inlineLabel()
                            ->prefixIcon('heroicon-m-table-cells')
                            ->required()
                            ->rules(EavEntityRule::rules()[EavEntityRule::ENTITY])
                            ->disabledOn('edit')->dehydrated()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make(EavInterface::ATTRIBUTE_ENTITY_ID)->label('Entity ID')->inlineLabel()
                            ->prefixIcon('heroicon-m-key')
                            ->required()
                            ->rules(EavEntityIdRule::rules()[EavEntityIdRule::ENTITY_ID])
                            ->disabledOn('edit')->dehydrated()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make(EavInterface::ATTRIBUTE_NAME)->label('Attribute Name')->inlineLabel()
                            ->prefixIcon('heroicon-m-finger-print')
                            ->rules(EavNameRule::rules()[EavNameRule::NAME])
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make(EavInterface::ATTRIBUTE_VALUE)->label('Attribute Value')->inlineLabel()
                            ->prefixIcon('heroicon-m-document-text')
                            ->rules(EavValueRule::rules()[EavValueRule::VALUE])
                            ->columnSpanFull(),
                    ])->columns(12)->columnStart(['sm' => 'full', 'md' => 2])->columnSpan(['sm' => 'full', 'md' => 10]),
                ])->columns(12)->columnSpanFull(),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->defaultGroup(
                Tables\Grouping\Group::make(EavInterface::ATTRIBUTE_ENTITY)->label('Entity')->collapsible(),
            )
            ->defaultGroup(EavInterface::ATTRIBUTE_ENTITY)
            ->columns([
                Tables\Columns\TextColumn::make(EavInterface::ATTRIBUTE_ENTITY)->label('Entity')
                    ->fontFamily(FontFamily::Mono)
                    ->formatStateUsing(fn(Model $entity): string => sprintf('%s', $entity->getModelEntity()->getId()))
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make(EavInterface::ATTRIBUTE_NAME)->label('Attribute Name')
                    ->fontFamily(FontFamily::Mono)
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make(EavInterface::ATTRIBUTE_VALUE)->label('Entity Value')
                    ->fontFamily(FontFamily::Mono)
                    ->searchable(isIndividual: true)
                    ->limit(25),
                Tables\Columns\TextColumn::make(EavInterface::ATTRIBUTE_CREATED_AT)->label(sprintf('Created (%s)', config('app.timezone')))
                    ->fontFamily(FontFamily::Mono)
                    ->dateTime(timezone: config('app.timezone')),
                Tables\Columns\TextColumn::make(EavInterface::ATTRIBUTE_UPDATED_AT)->label(sprintf('Updated (%s)', config('app.timezone')))
                    ->fontFamily(FontFamily::Mono)
                    ->dateTime(timezone: config('app.timezone')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->searchable(false);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEavStandards::route('/'),
            'create' => Pages\CreateEavStandard::route('/create'),
            'edit' => Pages\EditEavStandard::route('/{record}/edit'),
        ];
    }
}
