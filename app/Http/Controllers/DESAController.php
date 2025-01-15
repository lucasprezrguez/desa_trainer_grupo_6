<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Models\Device;
use Illuminate\Http\Request;

class DESAController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        $nombre = gethostname();
        $scenarios = Scenario::all();
        return view('admin.devices.index', compact('devices', 'scenarios'))->with('nombre', $nombre);
    }

    public function create()
    {
        return view('admin.devices.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('device_images', 'public');
    }

    Device::create([
        'name' => $validated['name'],
        'image' => $imagePath,
    ]);

    return redirect()->route('devices.index')->with('success', 'Dispositivo creado exitosamente.');
    }

    public function edit($id)
    {
        $device = Device::findOrFail($id);
        return view('devices.edit', compact('device'));
    }
    

    public function update(Request $request, Device $device)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'on_led' => 'required|boolean',
            'pause_state' => 'required|boolean',
            'display_message' => 'nullable|string',
        ]);

        $device->update($request->all());

        return redirect()->route('admin.devices.index')->with('success', 'DESA actualizado con éxito.');
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('admin.devices.index')->with('success', 'DESA eliminado con éxito.');
    }
}