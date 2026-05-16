<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Pages\PetugasLoanReport;
use App\Filament\Resources\Tickets\TicketResource;
use App\Filament\Support\LoanReportHtml2Media;
use App\Support\LoanReportAccess;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewLoanReport')
                ->label('Laporan Semua')
                ->icon('heroicon-o-document-text')
                ->url(PetugasLoanReport::getUrl())
                ->visible(fn (): bool => LoanReportAccess::canAccess()),
            LoanReportHtml2Media::exportPdfAction(),
            LoanReportHtml2Media::printAction(),
            CreateAction::make(),
        ];
    }
}
