<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSanitaryIssuesRequest;
use App\Models\SanitaryIssues;
use Illuminate\Http\Request;
use App\Http\Resources\SanitaryIssuesResource;
use Illuminate\Contracts\Cache\Store;
use App\Exports\SanitaryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;


class SanitaryIssuesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sanitaryissue= SanitaryIssues::with('employee')->get();
       return $sanitaryissue;
    }

    public function export()
    {
         $filename = 'sanitary_issues.xlsx'; // Desired filename
        $format = \Maatwebsite\Excel\Excel::XLSX; // Desired file format (XLSX or CSV)
    
        return Excel::download(new SanitaryExport(), $filename, $format);
       
    }
    public function exportToPDF()
        {
            $employees =SanitaryIssues::with(['employee'])->get();
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $pdf = PDF::loadView('exports.sanitary_pdf', ['employees' => $employees]);
            $pdf->getDomPDF()->set_option('font_size', 4);
            $pdf->getDomPDF()->set_option('table_width_auto', false);
            $pdf->getDomPDF()->set_option('table_width', '100%');
        
            $pdf->getDomPDF()->setOptions($options);
            
            return $pdf->download(' sanitary_issues.pdf');
            
        }
    
    
}
