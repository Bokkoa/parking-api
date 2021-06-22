<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Parking;

class Vehicle extends Model
{
    use HasFactory;

    public $incrementing = false;
    
    protected $table = 'vehicles';
    
    protected $primaryKey = 'license_plate';
    protected $keyType = 'string';

    protected $fillable = [
        'license_plate',
        'client_id',
    ];

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function clientName(){
        return $this->belongsTo('App\Models\Client');
    }

    public function parkings(){
        return $this->hasMany('App\Models\Parking', 'vehicle_id', 'license_plate');
    }

    public static function generateDailyReport( ){
        

        $vehicles = \DB::table('vehicles')
                            ->select(\DB::raw('group_concat(clients.name) as client_type'))
                            ->selectRaw('vehicles.license_plate, 
                             SUM(parkings.total_minutes) as total_minutes, SUM(parkings.cost) as total_cost')
                            ->leftJoin('clients', 'vehicles.client_id', '=', 'clients.id')
                            ->leftJoin('parkings', 'parkings.vehicle_id', '=', 'vehicles.license_plate')
                            ->whereRaw('Date(parkings.created_at) = CURDATE()')
                            ->groupBy('vehicles.license_plate')
                            ->get();
        
        $vehiclesCount = Vehicle::whereHas('parkings', function ($query) {
            return $query->whereDate('created_at', date('Y-m-d'));
        })->count();

        $dailyTotalCost = Parking::whereDate('created_at', date('Y-m-d'))->sum('cost');

        $report = [
            'total_vehicles_today' => $vehiclesCount,
            'daily_total_cost' => $dailyTotalCost,
            'vehicles' => $vehicles
        ];

        return $report;
        
    } 

}
