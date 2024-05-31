<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class TestsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $tests;
    protected $counter = 1;

    public function __construct($tests)
    {
        $this->tests = $tests;
    }

    /**
     * Define the headings for the columns
     * @return array
     */
    public function headings(): array
    {
        return [
            '№',
            'Название',
            'Описание',
            'Автор',
            'Время на выполнение',
            'Попытки',
            'Ограничение вопросов',
            'Опубликован',
            'Дата создания',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->tests;
    }

    /**
     * Map the data for each row
     * @param mixed $test
     * @return array
     */
    public function map($test): array
    {
        return [
            $this->counter++,
            $test->title,
            $test->desc,
            $test->author->last_name . ' ' . $test->author->first_name,
            $test->time_complete ?? 'Неограничено',
            $test->attempts ?? 'Неограничено',
            $test->limit_questions ?? 'Неограничено',
            $test->published ? 'Опубликовано' : 'Необликовано',
            $test->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Register events for further customization
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $cellRange = 'A1:I1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

                $sheet->getColumnDimension('C')->setWidth(50);
                $sheet->getStyle('C')->getAlignment()->setWrapText(true);
                $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                $sheet->getRowDimension('1')->setRowHeight(20);
                for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(-1);
                }
            },
        ];
    }
}
