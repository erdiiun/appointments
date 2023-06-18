<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyService;
use App\Models\CompanyWorkDay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;

class CompanyController extends Controller
{
    /**
     * Get list of companies
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $companies = Company::where('status', '=', 1)->get();

        if (count($companies) > 0 ) {
            return response()->json($companies);
        }

        Log::channel('custom')->error('There is no valid company');
        return response()->json([
            'status' => 'error',
            'message', 'There is no valid company'
        ], 400);

    }

    /**
     * Get list of companies services
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
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
            Log::channel('custom')->error($validator->errors()->first());
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        $company = DB::table('company_services')
            ->leftJoin('services', 'company_services.service_id', '=', 'services.id')
            ->leftJoin('companies', 'company_services.company_id', '=', 'companies.id')
            ->select('company_services.service_id', 'services.name', 'company_services.duration' )
            ->where('company_services.company_id', '=', $request->input('company_id'))
            ->where('companies.status', '=', 1)
            ->get();

        if (count($company) > 0) {
            return response()->json([
                'status' => 'success',
                'message' => $company
            ]);
        }else{
            Log::channel('custom')->error('Company does not have any service');
            return response()->json([
                'status' => 'error',
                'message' => 'Company does not have any service'
            ]);
        }
    }

    /**
     * Get list of company work days and hours
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function workDay(Request $request)
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
            Log::channel('custom')->error($validator->errors()->first());
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        $company_work_days = CompanyWorkDay::where('company_id', $request->input('company_id'))
            ->select(DB::raw("
                CASE
                    WHEN day_of_week = 1 THEN 'Pazartesi'
                    WHEN day_of_week = 2 THEN 'Salı'
                    WHEN day_of_week = 3 THEN 'Çarşamba'
                    WHEN day_of_week = 4 THEN 'Perşembe'
                    WHEN day_of_week = 5 THEN 'Cuma'
                    WHEN day_of_week = 6 THEN 'Cumartesi'
                    WHEN day_of_week = 7 THEN 'Pazar'
                END AS day_name
            "), 'start_time', 'end_time')->get();

        if (count($company_work_days) > 0 ) {
            return response()->json([
                'status' => 'success',
                'message' => $company_work_days
            ]);
        }else {
            Log::channel('custom')->error('Company does not have any work day');
            return response()->json([
                'status' => 'error',
                'message' => 'Company does not have any work day'
            ]);
        }
    }
}
