<?php

namespace App\Filament\Resources\Admin\Master\PositionResource\Pages;

use App\Filament\Resources\Admin\Master\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
