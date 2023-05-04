<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSanitaryIssuesRequest;
use App\Models\SanitaryIssues;
use Illuminate\Http\Request;
use App\Http\Resources\SanitaryIssuesResource;
use Illuminate\Contracts\Cache\Store;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
  

    /**
     * Display the specified resource.
     */

    
}
