<?php

namespace App\Filament\Resources\Admin\OKR\OkrResource\Pages;

use App\Filament\Resources\Admin\OKR\OkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOkrs extends ListRecords
{
    protected static string $resource = OkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
