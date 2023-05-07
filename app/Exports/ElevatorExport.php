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

class ElevatorExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles
{
    public function query()
    {
        // Customize this method to retrieve the data you want to export
       // return Elevator::query()->select('id_elevator', 'name', 'created_at');
       return Elevator::with('location')->select(
        'elevator.id_elevator',
        'elevator.name',
        'location.ville',
        'location.adress',
        'location.longitude',
        'location.latitude',
        
    )
    ->join('location', 'elevator.id_location', '=', 'location.id_location');
}
    
    

    public function headings(): array
    {
        // Define the column headings for the exported Excel file
        return [
            'Id elevator',
            'name',
              'ville', 
              'adress',
            'longitude',
            'latitude',
           
          
        
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
