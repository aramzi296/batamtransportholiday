<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleCalendar;

class AdminVehicleCalendarController extends Controller
{
    public function index($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $calendar = VehicleCalendar::where('vehicle_id', $vehicleId)->orderBy('date')->get();
        return view('admin.vehicles.calendar', compact('vehicle', 'calendar'));
    }

    public function create($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        return view('admin.vehicles.block_date', compact('vehicle'));
    }

    public function store(Request $request, $vehicleId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|max:255',
        ]);
        VehicleCalendar::updateOrCreate([
            'vehicle_id' => $vehicleId,
            'date' => $request->date,
        ], [
            'blocked_by' => 'admin',
            'reason' => $request->reason,
        ]);
        return redirect()->route('admin.vehicles.calendar', $vehicleId)
            ->with('success', 'Tanggal berhasil diblokir untuk kendaraan ini.');
    }

    public function destroy($vehicleId, $calendarId)
    {
        $calendar = VehicleCalendar::where('vehicle_id', $vehicleId)->findOrFail($calendarId);
        $calendar->delete();
        return redirect()->route('admin.vehicles.calendar', $vehicleId)
            ->with('success', 'Blok tanggal berhasil dihapus.');
    }
}
