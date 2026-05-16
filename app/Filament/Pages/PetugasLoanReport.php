<?php

namespace App\Filament\Pages;

use App\Filament\Support\LoanReportHtml2Media;
use App\Support\LoanReportAccess;
use App\Support\LoanReportBuilder;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PetugasLoanReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static ?string $title = 'Laporan Semua Peminjaman';

    protected static ?string $navigationLabel = 'Laporan Semua Peminjaman';

    protected static string|UnitEnum|null $navigationGroup = 'Peminjaman';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.petugas-loan-report';

    protected static ?string $slug = 'laporan-peminjaman';

    public array $reportData = [];

    public static function canAccess(): bool
    {
        return LoanReportAccess::canAccess();
    }

    public function mount(): void
    {
        abort_unless(LoanReportAccess::canAccess(), 403);

        $this->reportData = LoanReportBuilder::build();
    }

    protected function getHeaderActions(): array
    {
        return [
            LoanReportHtml2Media::exportPdfAction(),
            LoanReportHtml2Media::printAction(),
        ];
    }

    protected function getViewData(): array
    {
        return $this->reportData;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return LoanReportAccess::canAccess();
    }
}
