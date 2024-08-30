<?php

namespace App\Http\Controllers;

use App\HistoryLog;
use App\Http\Libs\Pdf;

use Illuminate\Http\Request;
use App\User;
use App\Loading;
use App\Order;
use App\Vehicle;
use Illuminate\Http\Response;

class VehicleApiController extends Controller
{

    protected $document;

    public function __construct()
    {
    }

    public function index(\Request $request)
    {
        return response()->json(['success' => true]);
    }

    public function post(\Request $request)
    {
        $validator = \Validator::make($request::all(), $this->getRules(0));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        return response()->json(['success' => true]);
    }

    protected function getRules($id)
    {
        $rule = [
            'plate'                => 'required',
            'driver_id'            => 'required',
            'wheight'              => 'required|numeric',
            //'estimated_time'       => 'required',
            'truck_unloading_date' => 'required',
            'truck_unloading_time' => 'required',
            //'name_document'        => 'required',
            'carrier_id'           => 'required',
            'freight'              => 'required'
        ];

        return $rule;
    }

    public function updateVehicle(Request $request, int $truckId) : Response
    {
        $allowedFields = [
            'status'
        ];
    
        $data = $request->only($allowedFields);
        $vehicle = Vehicle::whereId($truckId);
        $vehicleUpdated = $vehicle->update($data);
        $vehicle = $vehicle->first();
        
        if ($vehicleUpdated) {

            $causer = User::getUserLogged();
            $subjectType = 'vehicles';

            $log = json_encode([
                'causer_name' => $causer->name,
                'id' => $vehicle->id,
                'loadings.vehicles.status' => $data
            ]);

            $historyLog = new HistoryLog();
            $historyLog->log = $log;
            $historyLog->causer_id = $causer->id;
            $historyLog->subject_type = $subjectType;
            $historyLog->subject_id = $vehicle->id;

            $historyLog->save();

        }

        return response(null, $vehicle->toJson() ? Response::HTTP_NO_CONTENT : Response::HTTP_NOT_FOUND);
    }

    protected function updateFieldByPath($modelInstance, $fieldPath, $value)
    {
        $fieldComponents = explode('.', $fieldPath);
        
        $current = $modelInstance;

        for ($i = 0; $i < count($fieldComponents) - 1; $i++) {
            $relation = $fieldComponents[$i];

            if (method_exists($current, $relation)) {
                $current = $current->$relation()->first();
                
                if (!$current) {
                    return null;
                }
            } else {
                return null;
            }
        }
        
        $fieldName = end($fieldComponents);
        $current->$fieldName = $value;

        $current->save();
        return ['modelInstance' => $current, 'subjectType' => class_basename($current)];
    }
}
