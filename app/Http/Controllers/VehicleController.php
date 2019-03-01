<?php

namespace App\Http\Controllers;

use App\Services\Vehicle\IVehicleDetails;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleController extends Controller
{
    
    private $vehicle;

    public function __construct(IVehicleDetails $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function getCar(Request $request, $id)
    { 
        $result = $this->vehicle->getVehicle($request, $id);

        if (!$result) {
            throw new NotFoundHttpException();
        }

        return $result;
    }
    
}
