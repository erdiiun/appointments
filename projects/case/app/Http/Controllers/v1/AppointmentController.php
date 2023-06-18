<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\CompanyService;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Validator;

class AppointmentController extends Controller
{
    /**
     * Create an appointment
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function appointment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'company_id' => 'required|integer|exists:companies,id',
                'service_ids' => 'required',
                'customer_name' => 'required|max:75',
                'customer_surname' => 'required|max:75',
                'appointment_date' => 'required',
                'customer_phone' => 'required|max:11',
            ],
            [
                'company_id.required' => 'Company ID required',
                'company_id.integer' => 'Company ID should be integer',
                'company_id.exists' => 'Company ID is not valid',
                'customer_name.required' => 'Customer name is required',
                'customer_surname.required' => 'Customer surname is required',
                'customer_name.max' => 'The length of the Customer name must be less than 75',
                'customer_surname.max' => 'The length of the Customer surname must be less than 75',
                'service_ids.required' => 'Service ids are required',
                'appointment_date.required' => 'Appointment date is required',
                'customer_phone.required' => 'Customer phone is required',
                'customer_phone.max' => 'The length of the Customer phone must be less than 11'
            ]
        );


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        $date_string = $request->input('appointment_date');
        $company_id = $request->input('company_id');
        $request_service_ids = explode(',', $request->input('service_ids'));
        $customer_name = $request->input('customer_name');
        $customer_surname = $request->input('customer_surname');
        $customer_phone = $request->input('customer_phone');

        try {
            // Check the input date format
            $appointment_start_time = Carbon::createFromFormat('Y-m-d H:i', $date_string);
            $day_of_week = Carbon::parse($date_string)->format('N');
            $hour = Carbon::parse($date_string)->format('H:i');
        }catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Date format is not valid'
            ], 400);
        }

        // Check if the appointment time is between working hours
        $check_company_work_hour = DB::table('company_work_days')
            ->where('company_id', $company_id)
            ->where('day_of_week', $day_of_week)
            ->where('start_time', '<=', $hour)
            ->where('end_time', '>=', $hour)
            ->count() > 0;

        if (!$check_company_work_hour) {
            return response()->json([
                'status' => 'error',
                'message' => 'The company is not operating on the date you specified.'
            ], 400);
        }

        // Service ids that belongs the company
        $service_ids = CompanyService::where('company_id', $company_id)
            ->pluck('service_id')
            ->toArray();

        // Check if the request service ids in the company service ids
        if (count(array_diff($request_service_ids, $service_ids)) > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'The services you specified are not in the company service list.'
            ], 400);
        }

        // Get total service duration
        $get_total_appointment_minutes = CompanyService::where('company_id', $company_id)
            ->whereIn('service_id', $request_service_ids)
            ->sum('duration');

        // Calculate the appointment end time
        $appointment_end_time = $appointment_start_time
            ->copy()
            ->addMinutes($get_total_appointment_minutes)
            ->format('H:i');

        // Check if the appointment end time is within working hours
        $check_company_work_hour = DB::table('company_work_days')
                ->where('company_id', $company_id)
                ->where('day_of_week', $day_of_week)
                ->where('end_time', '>=', $appointment_end_time)
                ->count() > 0;

        if (!$check_company_work_hour) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment end time exceeds company closing time'
            ], 400);
        }

        // Check if there is another appointment at the desired appointment time
        $company_appointments = Appointment::where('company_id', $company_id)
            ->where(function ($query) use ($appointment_start_time, $appointment_end_time) {
            $query->where('app_start_date', '<=', $appointment_end_time)
                ->where('app_finish_date', '>=', $appointment_start_time);
            })
            ->orWhere(function ($query) use ($appointment_start_time, $appointment_end_time) {
                $query->where('app_start_date', '<=', $appointment_start_time)
                    ->where('app_finish_date', '>=', $appointment_end_time);
            })
            ->count() > 0;

        if ($company_appointments) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment times overlap with another appointment'
            ], 400);
        }

        // Add new appointments
        try {
            $new_appointment_end_time = '';
            foreach ($request_service_ids as $service_id) {
                $service = CompanyService::where('service_id', $service_id)
                    ->where('company_id', $company_id)
                    ->firstOrFail();

                if (!empty($new_appointment_end_time)) {
                    $appointment_start_time = $new_appointment_end_time;
                    $new_appointment_end_time = Carbon::createFromFormat('Y-m-d H:i', $new_appointment_end_time)
                        ->copy()
                        ->addMinutes($service->duration)
                        ->format('Y-m-d H:i');
                }else{
                    $new_appointment_end_time = $appointment_start_time
                        ->copy()
                        ->addMinutes($service->duration)
                        ->format('Y-m-d H:i');
                }
                Appointment::create([
                    'company_id' => $company_id,
                    'service_id' => $service_id,
                    'app_start_date' => $appointment_start_time,
                    'app_finish_date' => $new_appointment_end_time,
                    'customer_name_surname' => $customer_name.' '.$customer_surname,
                    'customer_phone' => $customer_phone
                ]);
            }
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating an appointment'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Appointments created with successfully'
        ]);
    }
}
