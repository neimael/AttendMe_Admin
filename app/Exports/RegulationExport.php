<?php

namespace App\Exports;

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
use App\Models\PresenceRegulation;

class RegulationExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles
{
    public function query()
    {
        // Customize this method to retrieve the data you want to export
       // return Elevator::query()->select('id_elevator', 'name', 'created_at');
       return PresenceRegulation::with('employee')->select(
       'presence_regulation.id_presence_regulation',
       'users.first_name',
         'users.last_name',
            'presence_regulation.attendance_day',
            'presence_regulation.check_in',
            'presence_regulation.check_out',
            'presence_regulation.status',

            'presence_regulation.issue_type',
            'presence_regulation.report',
            
        
    )
    ->join('users', 'presence_regulation.id_employee', '=', 'users.id');
}
    
    

    public function headings(): array
    {
        // Define the column headings for the exported Excel file
        return [
            'Id Presence Regulation',
            'first_name',
              'last_name', 
              'attendance_day',
            'check_in',
            'check_out',
            'status',
            'issue_type',
            'report',
            
           
          
        
            ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the worksheet
        $sheet->getStyle('A1:J1')->applyFromArray([
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
