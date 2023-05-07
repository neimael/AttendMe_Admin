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
use App\Models\SanitaryIssues;

class SanitaryExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles
{
    public function query()
    {
        // Customize this method to retrieve the data you want to export
       // return Elevator::query()->select('id_elevator', 'name', 'created_at');
       return SanitaryIssues::with('employee')->select(
       'sanitary_regulation.id_sanitary',
       'users.first_name',
         'users.last_name',
            'sanitary_regulation.report',
            'sanitary_regulation.certificate',
            'sanitary_regulation.created_at',
            
        
    )
    ->join('users', 'sanitary_regulation.id_employee', '=', 'users.id');
}
    
    

    public function headings(): array
    {
        // Define the column headings for the exported Excel file
        return [
            'Id Sanitary Regulation',
            'first_name',
              'last_name', 
              'report',
            'certificate',
            'created_at',
           
            
           
          
        
            ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the worksheet
        $sheet->getStyle('A1:F1')->applyFromArray([
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
