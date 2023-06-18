<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class CompanyController extends Controller
{
    public function list()
    {
        $companies = Company::where('status', '=', 1)->get();

        if (count($companies) > 0 ) {
            return response()->json($companies);
        }

        return response()->json(['error', 'There is no valid company'], 400);

    }

    public function service(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'company_id' => 'required|integer|exists:companies,id',
            ],
            [
                'company_id.required' => 'Company ID required',
                'company_id.integer' => 'Company ID should be integer',
                'company_id.exists' => 'Company ID is not valid'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $company = DB::table('company_services')
            ->leftJoin('services', 'company_services.service_id', '=', 'services.id')
            ->leftJoin('companies', 'company_services.company_id', '=', 'companies.id')
            ->select('company_services.service_id', 'services.name', 'company_services.duration' )
            ->where('company_services.company_id', '=', $request->input('company_id'))
            ->where('companies.status', '=', 1)
            ->get();

        if (count($company) > 0) {
            return response()->json($company);
        }else{
            return response()->json(['error' => 'Company does not have any service']);
        }

    }
}
