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
use App\Models\User;

class EmployeeExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles, WithDrawings
{
    public function query()
    {
        // Customize this method to retrieve the data you want to export
        return User::query()->select('first_name', 'last_name', 'email', 'phone_number', 'cin', 'adress', 'birthday', 'avatar');
    }

    public function headings(): array
    {
        // Define the column headings for the exported Excel file
        return [
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'CIN',
            'Adress',
            'Birthday',
            'Avatar',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the worksheet
        $sheet->getStyle('A1:H1')->applyFromArray([
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

    public function drawings()
    {
        // Retrieve the avatar images and create drawings for each one
        $drawings = [];

        $users = User::all();

        foreach ($users as $index => $user) {
            $avatarPath = public_path('EmployeeAvatar/' . $user->avatar);
            if (file_exists($user->avatar)) {
                $drawing = new Drawing();
                $drawing->setName('Avatar');
                $drawing->setDescription('Avatar');
                $drawing->setPath($avatarPath);
                $drawing->setHeight(80);
                $drawing->setWidth(80);
                $drawing->setCoordinates('H' . ($index + 2));

                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }
}
