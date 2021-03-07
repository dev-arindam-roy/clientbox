<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Carbon\Carbon;

class ClientsExport implements FromArray , WithHeadings , ShouldAutoSize , WithColumnWidths , WithStyles, WithColumnFormatting
{
    use Exportable;
    protected $clients;

    public function __construct(array $clients)
    {
        $serialNo = 1;
        $questionLevel = array('0' => 'Easy', '1' => 'Medium', '2' => 'Standrad', '3' => 'Hard');
        $this->clients = [];
        foreach ($clients as $v) {
            $arr = array();
            $arr['serial_no'] = $serialNo;
            $arr['first_name'] = $v['first_name'];
            $arr['last_name'] = $v['last_name'];
            $arr['email_id'] = $v['email_id'];
            $arr['phno'] = $v['phno'];
            $arr['country'] = $v['country'];
            $arr['city'] = $v['city'];
            $arr['note'] = $v['note'];

            $arr['created'] = Carbon::parse($v['created_at'])->format('d-m-Y');
            if ($v['updated_at'] != null) {
                $arr['updated'] = Carbon::parse($v['updated_at'])->format('d-m-Y');
            } else {
                $arr['updated'] = ''; 
            }
            
            array_push($this->clients, $arr);
            $serialNo++;
        }
    }

    public function array(): array
    {
        return $this->clients;
    }

    //Doc Ref - https://docs.laravel-excel.com/3.1/exports/collection.html

    public function headings(): array
    {
        return [
            '#',
            'First Name',
            'Last Name',
            'Email-Id',
            'Phone Number',
            'Country',
            'City',
            'Note',
            'Created At',
            'Updated At'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'H' => 80,        
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            //'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //'M'  => ['font' => ['bold' => true]],
        ];

        //another way
        //$sheet->getStyle('B2')->getFont()->setBold(true);
    }

}
