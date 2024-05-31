<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;


class usersExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{

    protected $users;
    protected $counter = 1;

    public function __construct($users){
        $this->users = $users;
    }

    /**
     * Define the headings for the columns
     * @return array
     */
    public function headings(): array
    {
        return [
            '№',
            'Имя',
            'Фамилия',
            'Отчество',
            'Email',
            'Дата регистрации',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->users;
    }

    /**
     * Map the data for each row
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $this->counter++,
            $user->first_name,
            $user->last_name,
            $user->middle_name,
            $user->email,
            $user->created_at->format('Y-m-d H:i:s'),
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
                $cellRange = 'A1:F1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
