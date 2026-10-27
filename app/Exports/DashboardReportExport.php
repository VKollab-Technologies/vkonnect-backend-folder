<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * The Super Admin dashboard as a real .xlsx — the same KPI boxes, the same
 * three graphs, the same System Health status the screen shows.
 *
 * Previously "Export" produced a CSV of bare numbers: no charts (CSV cannot
 * hold them) and no System Health, so the file did not match the dashboard it
 * claimed to export. Charts here are NATIVE Excel
 * charts bound to the data ranges below, not images, so they stay live if the
 * numbers are edited.
 */
class DashboardReportExport
{
    public function __construct(
        private readonly array $dashboard,
        private readonly array $health,
    ) {}

    public function build(): Spreadsheet
    {
        $book = new Spreadsheet();
        $book->getProperties()
            ->setTitle('VKONNECT — Platform Overview')
            ->setCreator('VKONNECT');

        $this->buildOverview($book->getActiveSheet());
        $this->buildCharts($book);

        $book->setActiveSheetIndex(0);

        return $book;
    }

    /** Sheet 1 — the KPI boxes and System Health. */
    private function buildOverview(Worksheet $sheet): void
    {
        $stats = $this->dashboard['stats'];

        $sheet->setTitle('Overview');
        $sheet->setCellValue('A1', 'VKONNECT — Platform Overview');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->setCellValue('A2', 'Generated '.now()->format('d M Y, H:i'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->getColor()->setARGB('FF64748B');

        $this->headerRow($sheet, 4, ['Metric', 'Value']);

        // Same order as the dashboard tiles, so the file reads like the screen.
        // Headline tiles first, then the secondary counts -- same order and
        // same grouping as the screen, so the file reads like the dashboard.
        $rows = [
            ['Total Alumni', $stats['totalAlumni']],
            ['Active Users', $stats['totalActiveUsers']],
            ['Disabled Users', $stats['totalDisabledUsers']],
            ['HR Admins', $stats['totalHrAdmins']],
            ['Super Admins', $stats['totalSuperAdmins']],
            ['Total Jobs', $stats['totalJobs']],
            ['Documents', $stats['totalDocuments']],
            ['Applications', $stats['totalApplications']],
            ['Notifications', $stats['totalNotifications']],
            ['Announcements', $stats['totalAnnouncements']],
            ['Active Alert Tickers', $stats['totalActiveAlertTickers']],
        ];

        $r = 5;
        foreach ($rows as [$label, $value]) {
            $sheet->setCellValue("A{$r}", $label);
            $sheet->setCellValueExplicit("B{$r}", (string) $value, DataType::TYPE_NUMERIC);
            $r++;
        }

        // System Health — the dashboard's 12th tile.
        $r++;
        $sheet->setCellValue("A{$r}", 'System Health');
        $sheet->getStyle("A{$r}")->getFont()->setBold(true)->setSize(13);
        $r++;
        $this->headerRow($sheet, $r, ['Component', 'Status']);
        $r++;
        $sheet->setCellValue("A{$r}", 'Overall');
        $sheet->setCellValue("B{$r}", strtoupper((string) ($this->health['status'] ?? 'unknown')));
        $r++;
        foreach (($this->health['checks'] ?? []) as $component => $state) {
            $sheet->setCellValue("A{$r}", ucfirst((string) $component));
            $sheet->setCellValue("B{$r}", (string) $state);
            $r++;
        }

        foreach (['A' => 30, 'B' => 16] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }

    /** Sheet 2 — the three dashboard graphs, as native Excel charts. */
    private function buildCharts(Spreadsheet $book): void
    {
        $sheet = $book->createSheet();
        $sheet->setTitle('Charts');

        // --- Alumni Growth (area on screen -> line here) ---------------------
        $growth = $this->dashboard['alumniGrowth'] ?? [];
        $this->headerRow($sheet, 1, ['Month', 'Cumulative Alumni']);
        $row = 2;
        foreach ($growth as $point) {
            $sheet->setCellValue("A{$row}", $point['label']);
            $sheet->setCellValueExplicit("B{$row}", (string) $point['value'], DataType::TYPE_NUMERIC);
            $row++;
        }
        $growthEnd = $row - 1;

        if (count($growth) > 0) {
            $sheet->addChart($this->chart(
                name: 'alumniGrowth',
                title: 'Alumni Growth',
                type: DataSeries::TYPE_LINECHART,
                labelRef: "'Charts'!\$B\$1",
                categoryRef: "'Charts'!\$A\$2:\$A\${$growthEnd}",
                valueRef: "'Charts'!\$B\$2:\$B\${$growthEnd}",
                count: count($growth),
                topLeft: 'D1',
                bottomRight: 'L18',
            ));
        }

        // --- User Status (donut on screen -> pie here) -----------------------
        $status = $this->dashboard['userStatusBreakdown'] ?? [];
        $statusStart = $growthEnd + 3;
        $this->headerRow($sheet, $statusStart, ['User Status', 'Count']);
        $row = $statusStart + 1;
        foreach ($status as $slice) {
            $sheet->setCellValue("A{$row}", $slice['label']);
            $sheet->setCellValueExplicit("B{$row}", (string) $slice['value'], DataType::TYPE_NUMERIC);
            $row++;
        }
        $statusEnd = $row - 1;

        if (count($status) > 0) {
            $sheet->addChart($this->chart(
                name: 'userStatus',
                title: 'User Status',
                type: DataSeries::TYPE_PIECHART,
                labelRef: "'Charts'!\$B\$".$statusStart,
                categoryRef: "'Charts'!\$A\$".($statusStart + 1).":\$A\${$statusEnd}",
                valueRef: "'Charts'!\$B\$".($statusStart + 1).":\$B\${$statusEnd}",
                count: count($status),
                topLeft: 'D'.$statusStart,
                bottomRight: 'L'.($statusStart + 17),
            ));
        }

        // --- Documents by Category (bar) -------------------------------------
        $docs = $this->dashboard['documentsByCategory'] ?? [];
        $docsStart = $statusEnd + 3;
        $this->headerRow($sheet, $docsStart, ['Document Category', 'Count']);
        $row = $docsStart + 1;
        foreach ($docs as $bar) {
            $sheet->setCellValue("A{$row}", $bar['label']);
            $sheet->setCellValueExplicit("B{$row}", (string) $bar['value'], DataType::TYPE_NUMERIC);
            $row++;
        }
        $docsEnd = $row - 1;

        if (count($docs) > 0) {
            $sheet->addChart($this->chart(
                name: 'documentsByCategory',
                title: 'Documents by Category',
                type: DataSeries::TYPE_BARCHART,
                labelRef: "'Charts'!\$B\$".$docsStart,
                categoryRef: "'Charts'!\$A\$".($docsStart + 1).":\$A\${$docsEnd}",
                valueRef: "'Charts'!\$B\$".($docsStart + 1).":\$B\${$docsEnd}",
                count: count($docs),
                topLeft: 'D'.$docsStart,
                bottomRight: 'L'.($docsStart + 17),
            ));
        }

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(18);
    }

    private function chart(
        string $name,
        string $title,
        string $type,
        string $labelRef,
        string $categoryRef,
        string $valueRef,
        int $count,
        string $topLeft,
        string $bottomRight,
    ): Chart {
        $series = new DataSeries(
            $type,
            $type === DataSeries::TYPE_BARCHART ? DataSeries::GROUPING_CLUSTERED : null,
            [0],
            [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $labelRef, null, 1)],
            [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $categoryRef, null, $count)],
            [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $valueRef, null, $count)],
        );

        if ($type === DataSeries::TYPE_BARCHART) {
            $series->setPlotDirection(DataSeries::DIRECTION_COL);
        }

        $chart = new Chart(
            $name,
            new Title($title),
            new Legend(Legend::POSITION_RIGHT, null, false),
            new PlotArea(null, [$series]),
        );
        $chart->setTopLeftPosition($topLeft);
        $chart->setBottomRightPosition($bottomRight);

        return $chart;
    }

    private function headerRow(Worksheet $sheet, int $row, array $headings): void
    {
        $col = 'A';
        foreach ($headings as $heading) {
            $sheet->setCellValue("{$col}{$row}", $heading);
            $col++;
        }
        $last = chr(ord('A') + count($headings) - 1);
        $style = $sheet->getStyle("A{$row}:{$last}{$row}");
        $style->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2563EB');
        $style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    }
}
