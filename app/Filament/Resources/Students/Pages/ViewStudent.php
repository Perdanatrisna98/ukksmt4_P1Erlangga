<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Override;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    #[Override]
    public function getTitle(): string|Htmlable
    {
        return $this->record->user->name;
    }

    #[Override]
    function getBreadcrumb(): string
    {
        return $this->record->user->name;
    }
}
