<style>
    .report-wrap { font-family: Arial, sans-serif; color: #111827; }
    .report-wrap h1 { margin: 0 0 8px 0; font-size: 22px; }
    .report-wrap p { margin: 4px 0; }
    .report-meta { margin-bottom: 12px; }
    .report-summary { display: flex; gap: 12px; margin-top: 14px; margin-bottom: 8px; flex-wrap: wrap; }
    .report-summary-box { border: 1px solid #d1d5db; border-radius: 6px; padding: 10px 12px; min-width: 180px; }
    .report-summary-box .label { font-size: 11px; color: #6b7280; text-transform: uppercase; }
    .report-summary-box .value { font-size: 16px; font-weight: bold; margin-top: 2px; }
    .report-table { width: 100%; border-collapse: collapse; margin-top: 18px; }
    .report-table th, .report-table td { border: 1px solid #d1d5db; padding: 8px; font-size: 12px; text-align: left; vertical-align: top; }
    .report-table th { background: #f3f4f6; }
    .report-muted { color: #6b7280; font-size: 12px; }
    .report-small { font-size: 11px; color: #374151; }
</style>

<div class="report-wrap">
    <h1>{{ $title ?? 'Laporan Peminjaman Alat' }}</h1>
    <div class="report-meta">
        <p>Dicetak Oleh (Petugas): <strong>{{ $user->name }}</strong></p>
        <p>Email Petugas: <strong>{{ $user->email }}</strong></p>
        <p class="report-muted">Waktu Cetak: {{ $printedAt->format('d M Y H:i:s') }}</p>
    </div>

    <div class="report-summary">
        <div class="report-summary-box">
            <div class="label">Total Tiket</div>
            <div class="value">{{ $totals['total_tickets'] }}</div>
        </div>
        <div class="report-summary-box">
            <div class="label">Pengembalian Selesai</div>
            <div class="value">{{ $totals['total_returned'] }}</div>
        </div>
        <div class="report-summary-box">
            <div class="label">Total Denda</div>
            <div class="value">Rp {{ number_format($totals['total_fine'], 0, ',', '.') }}</div>
        </div>
        <div class="report-summary-box">
            <div class="label">Jumlah Pelanggaran</div>
            <div class="value">{{ $totals['total_violations'] }}</div>
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Nama Peminjam</th>
                <th>No Tiket</th>
                <th>Aset</th>
                <th>Kategori</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Pengembalian</th>
                <th>Denda / Asset Fine</th>
                <th>Pelanggaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tickets as $index => $ticket)
                @php
                    $returns = $ticket->AssetReturn;
                    $fines = $returns->flatMap(fn ($return) => $return->assetFines);

                    $violations = $fines->map(function ($fine) {
                        return match ($fine->type) {
                            'late' => 'Terlambat',
                            'damage' => 'Kerusakan',
                            'lost' => 'Kehilangan',
                            default => ucfirst($fine->type),
                        };
                    })->unique()->values();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div>Booking: {{ optional($ticket->booked_at)->format('d M Y H:i') ?? '-' }}</div>
                        <div>Pinjam: {{ optional($ticket->borrowed_at)->format('d M Y H:i') ?? '-' }}</div>
                        <div>Kembali: {{ optional($ticket->returned_at)->format('d M Y H:i') ?? '-' }}</div>
                    </td>
                    <td>{{ $ticket->user?->name ?? '-' }}</td>
                    <td>{{ $ticket->ticket_number }}</td>
                    <td>{{ $ticket->asset?->name }} ({{ $ticket->asset?->code }})</td>
                    <td>{{ $ticket->asset?->category?->name ?? '-' }}</td>
                    <td>{{ $ticket->qty }}</td>
                    <td>{{ ucfirst($ticket->status) }}</td>
                    <td>
                        @if ($returns->isEmpty())
                            <span>-</span>
                        @else
                            @foreach ($returns as $return)
                                <div>
                                    {{ optional($return->returned_at)->format('d M Y H:i') ?? '-' }}
                                    <span class="report-small">(Kondisi: {{ ucfirst($return->condition) }})</span>
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($fines->isEmpty())
                            <span>Rp 0</span>
                        @else
                            @foreach ($fines as $fine)
                                <div>
                                    {{ strtoupper($fine->type) }}:
                                    Rp {{ number_format((float) $fine->amount, 0, ',', '.') }}
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $violations->isNotEmpty() ? $violations->join(', ') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">Belum ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
