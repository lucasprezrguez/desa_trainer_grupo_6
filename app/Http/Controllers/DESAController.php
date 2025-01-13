<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DESAController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        $nombre = gethostname();
        return view('admin.devices.index', compact('devices'))->with('nombre', $nombre);
    }

    public function create()
    {
        return view('admin.devices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'on_led' => 'required|boolean',
            'pause_state' => 'required|boolean',
            'display_message' => 'nullable|string',
        ]);

        Device::create($request->all());

        return redirect()->route('admin.devices.index')->with('success', 'DESA creado con éxito.');
    }

    public function edit(Device $device)
    {
        return view('desa.edit', compact('device'));
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