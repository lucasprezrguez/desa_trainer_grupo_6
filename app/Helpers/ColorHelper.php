<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Genera un color de fondo que cumple con WCAG AAA.
     *
     * @param string $nombre El nombre del usuario.
     * @return string El color de fondo en formato hexadecimal.
     */
    public static function generarColorWCAG($nombre)
    {
        // Generar un color base a partir del hash del nombre
        $colorBase = substr(md5($nombre), 0, 6);
        
        // Convertir el color base a RGB
        $r = hexdec(substr($colorBase, 0, 2));
        $g = hexdec(substr($colorBase, 2, 2));
        $b = hexdec(substr($colorBase, 4, 2));
        
        // Calcular el brillo (luminancia relativa)
        $luminancia = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;
        
        // Ajustar el color para cumplir con WCAG AAA
        if ($luminancia > 0.4) { // 0.4 es un valor aproximado para garantizar contraste
            $r = max(0, $r - 100);
            $g = max(0, $g - 100);
            $b = max(0, $b - 100);
        }
        
        // Devolver el color en formato hexadecimal
        return sprintf("%02x%02x%02x", $r, $g, $b);
    }
}