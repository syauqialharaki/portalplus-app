<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UserProfileWidget extends Widget
{
    protected static string $view = 'filament.widgets.user-profile-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -10;

    public function getUser()
    {
        return auth()->user();
    }
}
