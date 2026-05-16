<?php

namespace App\Filament\Support;

use App\Models\Ticket;
use App\Support\LoanReportAccess;
use App\Support\LoanReportBuilder;
use Torgodly\Html2Media\Actions\Html2MediaAction;

class LoanReportHtml2Media
{
    public static function exportPdfAction(?Ticket $ticket = null): Html2MediaAction
    {
        return self::makeAction(
            name: $ticket ? 'exportTicketPdf' : 'exportLoanReportPdf',
            label: 'Export PDF',
            icon: 'heroicon-o-arrow-down-tray',
            color: 'success',
            ticket: $ticket,
            savePdf: true,
            print: false,
            modalHeading: $ticket
                ? 'Export PDF — Detail Peminjaman'
                : 'Export PDF — Laporan Peminjaman',
            modalDescription: 'Pratinjau di bawah. Klik "Save as PDF" untuk mengunduh.',
        );
    }

    public static function printAction(?Ticket $ticket = null): Html2MediaAction
    {
        return self::makeAction(
            name: $ticket ? 'printTicket' : 'printLoanReport',
            label: 'Cetak',
            icon: 'heroicon-o-printer',
            color: 'gray',
            ticket: $ticket,
            savePdf: false,
            print: true,
            modalHeading: $ticket
                ? 'Cetak — Detail Peminjaman'
                : 'Cetak Laporan Peminjaman',
            modalDescription: 'Pratinjau di bawah. Klik "Print" untuk mencetak.',
        );
    }

    protected static function makeAction(
        string $name,
        string $label,
        string $icon,
        string $color,
        ?Ticket $ticket,
        bool $savePdf,
        bool $print,
        string $modalHeading,
        string $modalDescription,
    ): Html2MediaAction {
        $action = Html2MediaAction::make($name)
            ->label($label)
            ->icon($icon)
            ->color($color)
            ->visible(fn (): bool => LoanReportAccess::canAccess())
            ->requiresConfirmation(false)
            ->modalHeading($modalHeading)
            ->modalDescription($modalDescription)
            ->preview()
            ->orientation('landscape')
            ->format('a4')
            ->margins(10)
            ->content(function (?Ticket $record = null) use ($ticket) {
                $target = $ticket ?? $record;

                $data = $target instanceof Ticket
                    ? LoanReportBuilder::buildForTicket($target)
                    : LoanReportBuilder::build();

                return view('filament.reports.loan-report', $data);
            })
            ->filename(function (?Ticket $record = null) use ($ticket) {
                $target = $ticket ?? $record;

                if ($target instanceof Ticket) {
                    return 'peminjaman-' . $target->ticket_number;
                }

                return 'laporan-peminjaman-' . now()->format('Y-m-d');
            });

        if ($savePdf) {
            $action->savePdf()->print(false);
        } else {
            $action->savePdf(false)->print();
        }

        return $action;
    }
}
