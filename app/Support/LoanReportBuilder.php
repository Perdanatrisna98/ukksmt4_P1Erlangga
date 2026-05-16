<?php

namespace App\Support;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class LoanReportBuilder
{
    public static function build(): array
    {
        $tickets = Ticket::query()
            ->with([
                'user:id,name,email',
                'asset:id,category_id,name,code',
                'asset.category:id,name',
                'AssetReturn.assetFines',
            ])
            ->latest('booked_at')
            ->get();

        return [
            'user' => auth()->user(),
            'printedAt' => now(),
            'title' => 'Laporan Peminjaman Alat',
            'tickets' => $tickets,
            'totals' => self::buildTotals($tickets),
        ];
    }

    public static function buildForTicket(Ticket $ticket): array
    {
        $ticket->loadMissing([
            'user:id,name,email',
            'asset:id,category_id,name,code',
            'asset.category:id,name',
            'AssetReturn.assetFines',
        ]);

        $tickets = collect([$ticket]);

        return [
            'user' => auth()->user(),
            'printedAt' => now(),
            'title' => 'Detail Peminjaman — ' . $ticket->ticket_number,
            'tickets' => $tickets,
            'totals' => self::buildTotals($tickets),
        ];
    }

    public static function buildTotals(Collection $tickets): array
    {
        $totalTickets = $tickets->count();
        $totalReturned = $tickets->where('status', 'returned')->count();

        $totalFine = $tickets
            ->flatMap(fn (Ticket $ticket) => $ticket->AssetReturn)
            ->flatMap(fn ($assetReturn) => $assetReturn->assetFines)
            ->sum('amount');

        $totalViolations = $tickets
            ->flatMap(fn (Ticket $ticket) => $ticket->AssetReturn)
            ->flatMap(fn ($assetReturn) => $assetReturn->assetFines)
            ->count();

        return [
            'total_tickets' => $totalTickets,
            'total_returned' => $totalReturned,
            'total_fine' => $totalFine,
            'total_violations' => $totalViolations,
        ];
    }
}
