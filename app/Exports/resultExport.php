<?php

namespace App\Exports;

use App\Enums\TestStatus;
use App\Models\Test;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;


class resultExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $test;
    protected $counter;

    public function __construct(Test $test)
    {
        $this->test = $test;
        $this->counter = 1;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->test->testUsers->sortBy([
            ['user.last_name', 'asc'],
            ['user.first_name', 'asc'],
            ['attempt', 'asc']
        ]);
    }

    /**
     * Define the headings for the columns
     * @return array
     */
    public function headings(): array
    {
        return [
            '№',
            'Пользователь',
            'Попытка',
            'Балл',
            'Процент',
            'Статус',
            'Дата начала',
            'Дата окончания'
        ];
    }

    /**
     * Map the data for each row
     * @param $testUser
     * @return array
     */
    public function map($testUser): array
    {
        return [
            $this->counter++,
            $testUser->user->last_name . ' ' . $testUser->user->first_name,
            $testUser->attempt,
            $testUser->score,
            $testUser->percent,
            $testUser->status == TestStatus::PASSED->value ? 'Проверен' : 'Не проверен',
            $testUser->created_at->format('Y-m-d H:i:s'),
            $testUser->test_end_at->format('Y-m-d H:i:s'),

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
                $cellRange = 'A1:H1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
