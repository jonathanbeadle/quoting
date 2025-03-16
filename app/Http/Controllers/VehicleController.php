<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Display the form to create a new vehicle.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vehicle.create');
    }
    
    /**
     * Store a new vehicle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate using the updated fields
        $data = $request->validate([
            'make'                => 'required|string|max:255',
            'model'               => 'required|string|max:255',
            'specification'       => 'required|string',
            'transmission'        => 'required|in:Manual,Automatic',
            'fuel_type'           => 'required|in:Diesel,Petrol,Hybrid,Electric',
            'registration_status' => 'required|in:New,Pre-Registered,Used',
            'registration_date'   => 'nullable|date',
            'additional_options'  => 'nullable|string',
            'dealer_fit_options'  => 'nullable|string',
            'colour'              => 'required|string|max:255',
        ]);
        
        // Create the vehicle record
        $vehicle = Vehicle::create($data);

        // Always return JSON if it's an AJAX request
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json($vehicle);
        }
        
        return redirect()->route('vehicle.create')->with('success', 'Vehicle added successfully!');
    }
    
    /**
     * List all vehicles with pagination.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('make', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('specification', 'like', '%' . $search . '%')
                  ->orWhere('fuel_type', 'like', '%' . $search . '%')
                  ->orWhere('registration_status', 'like', '%' . $search . '%')
                  ->orWhere('colour', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        $vehicles = $query->paginate(10)->withQueryString();
        return view('vehicle.index', compact('vehicles'));
    }
    
    /**
     * Show details for a specific vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $vehicle = Vehicle::with(['quotes' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'quotes.customer'])->findOrFail($id);
        return view('vehicle.show', compact('vehicle'));
    }
    
    /**
     * Display edit form for a vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicle.edit', compact('vehicle'));
    }
    
    /**
     * Update vehicle information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        try {
            $data = $request->validate([
                'make'                => 'required|string|max:255',
                'model'               => 'required|string|max:255',
                'specification'       => 'required|string',
                'transmission'        => 'required|in:Manual,Automatic',
                'fuel_type'           => 'required|in:Diesel,Petrol,Hybrid,Electric',
                'registration_status' => 'required|in:New,Pre-Registered,Used',
                'registration_date'   => 'nullable|date',
                'additional_options'  => 'nullable|string',
                'dealer_fit_options'  => 'nullable|string',
                'colour'              => 'required|string|max:255'
            ]);

            $vehicle->update($data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle updated successfully!',
                    'vehicle' => $vehicle
                ]);
            }

            return redirect()->route('vehicle.show', ['id' => $vehicle->id])
                           ->with('success', 'Vehicle updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        }
    }

    /**
     * Delete a vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicleName = $vehicle->make . ' ' . $vehicle->model;
        
        // Check if vehicle has associated quotes
        if ($vehicle->quotes()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete vehicle with associated quotes.');
        }
        
        $vehicle->delete();
        return redirect()->route('vehicle.index')->with('success', "Vehicle '{$vehicleName}' deleted successfully!");
    }
}
