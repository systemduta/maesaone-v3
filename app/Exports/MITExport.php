<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MITExport implements FromCollection, WithHeadings
{
    public $column;
    public $data;

    public function __construct($data, $column)
    {
        $this->column = $column;
        $this->data = $data;
    }

    public function headings(): array
    {
        $heading = [];
        foreach ($this->column as $column) {
            array_push($heading, $column['label']);
        }
        return $heading;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
//        dd($this->data, $this->column);
        return $this->data;
    }
}
