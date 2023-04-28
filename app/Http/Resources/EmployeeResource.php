<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SanitaryIssuesResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        //$data = parent::toArray($request);
       $data['full_name'] = $this->first_name . ' ' . $this->last_name;
        $data['sanitary_issues'] =SanitaryIssuesResource::collection($this->sanitaryIssues);
        return $data;


    }
}
