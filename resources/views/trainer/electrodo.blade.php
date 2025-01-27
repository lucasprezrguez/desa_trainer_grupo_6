@extends('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arrastrar Electrodos</title>
    <script src="{{ asset('js/drag-drop.js') }}" defer></script>
    <style>
        #contenedor-maniqui {
            position: relative;
            width: 300px;
            height: 500px;
            margin: auto;
        }
        .electrodo {
            width: 60px;
            height: 60px;
            position: absolute;
            cursor: grab;
            text-align: center;
            line-height: 60px;
            color: white;
            border-radius: 50%;
        }
        #electrodo-1 { background-color: red; }
        #electrodo-2 { background-color: blue; }
        #zona-1, #zona-2 {
            width: 50px;
            height: 50px;
            position: absolute;
            border: 2px dashed green;
        }
        #zona-1 { top: 150px; left: 80px; }
        #zona-2 { top: 150px; right: 80px; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div id="modal" class="bg-white p-6 rounded-lg w-3/4 h-3/4 relative">
        <!-- Maniquí -->
        <div id="contenedor-maniqui">
            <img src="{{ asset('images/maniqui.png') }}" alt="Maniquí" class="w-full h-full">

            <!-- Zonas de colocación -->
            <div id="zona-1"></div>
            <div id="zona-2"></div>

            <!-- Electrodos -->
            <div id="electrodo-1" class="electrodo" style="top: 20px; left: 20px;">
                Electrodo 1
            </div>
            <div id="electrodo-2" class="electrodo" style="top: 20px; right: 20px;">
                Electrodo 2
            </div>
        </div>
    </div>
</body>
</html>

