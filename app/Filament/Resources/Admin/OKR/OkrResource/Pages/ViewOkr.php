<?php

namespace App\Filament\Resources\Admin\OKR\OkrResource\Pages;

use App\Filament\Resources\Admin\OKR\OkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOkr extends ViewRecord
{
    protected static string $resource = OkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
