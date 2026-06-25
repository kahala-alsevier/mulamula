<?php

namespace App\Filament\Resources\FlowerMeanings\Pages;

use App\Filament\Resources\FlowerMeanings\FlowerMeaningResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFlowerMeanings extends ManageRecords
{
    protected static string $resource = FlowerMeaningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
