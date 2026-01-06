<?php

namespace App\Filament\Resources\Admin\OKR\TaskResource\Pages;

use App\Filament\Resources\Admin\OKR\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('board')
                ->label('Board')
                ->icon('heroicon-o-rectangle-stack')
                ->url(TaskResource::getUrl('board')),
            Actions\CreateAction::make(),
        ];
    }
}
