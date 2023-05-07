<?php

namespace App\Exports;

use App\Models\AssignmentElevator;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use App\Models\Elevator;

class AsignmentExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles
{
    public function query()
    {
        // Customize this method to retrieve the data you want to export
       // return Elevator::query()->select('id_elevator', 'name', 'created_at');
       return AssignmentElevator::with(['employee', 'qrcode.elevator.location'])
       ->select(
           'assignment_elevator.id_assignment_elevator',
           'users.first_name',
           'users.last_name',
           'qrcode.mission',
           'elevator.name as elevator_name',
           'location.ville',
           'location.adress'
       )
       ->join('users', 'assignment_elevator.id_employee', '=', 'users.id')
       ->join('qrcode', 'assignment_elevator.id_elevator', '=', 'qrcode.id_qr_code')
       ->join('elevator', 'qrcode.id_elevator', '=', 'elevator.id_elevator')
       ->join('location', 'elevator.id_location', '=', 'location.id_location');
}

    

    public function headings(): array
    {
        // Define the column headings for the exported Excel file
        return [
            'Id assignment',
            'first name',
              'last_name', 
              'mission',
            'elevator',
            'city',
            'address',
           
          
        
            ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the worksheet
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'DDDDDD',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }
}
