<?php

namespace App\Filament\Resources\Admin\Master\UnitResource\Pages;

use App\Filament\Resources\Admin\Master\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnit extends EditRecord
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
