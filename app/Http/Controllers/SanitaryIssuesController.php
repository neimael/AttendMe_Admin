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
        //
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
    public function store(StoreSanitaryIssuesRequest $request)
    {
        $sanitary_issues=new SanitaryIssues();
        $sanitary_issues->id_employee=1;
        $sanitary_issues->regulation_date=$request->regulation_date;
        $sanitary_issues->certificate=$request->certificate;
        $sanitary_issues->report=$request->report;
        $sanitary_issues->save();
        return new SanitaryIssuesResource($sanitary_issues);
    }

    /**
     * Display the specified resource.
     */
    public function show(SanitaryIssues $sanitaryIssues)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SanitaryIssues $sanitaryIssues)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SanitaryIssues $sanitaryIssues)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SanitaryIssues $sanitaryIssues)
    {
        //
    }
}
