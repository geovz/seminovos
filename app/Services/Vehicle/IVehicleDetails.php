<?php namespace App\Services\Vehicle;

interface IVehicleDetails
{
    public function getVehicle($request, $id);
    public function getResultSearch($crawler, $id);
    public function getImg($crawler);
    public function buildModel($dataVehicle);
}
