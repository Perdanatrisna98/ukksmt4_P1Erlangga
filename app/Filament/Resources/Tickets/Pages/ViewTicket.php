<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use App\Filament\Support\LoanReportHtml2Media;
use App\Support\LoanReportAccess;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        $actions = [
            EditAction::make(),
        ];

        if (LoanReportAccess::canAccess()) {
            $actions = [
                LoanReportHtml2Media::exportPdfAction($record),
                LoanReportHtml2Media::printAction($record),
                ...$actions,
            ];
        }

        return $actions;
    }
}
