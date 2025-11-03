<?php

trait Cadenas
{
    public function longitud(string $cadena): int
    {
        return mb_strlen($cadena);
    }
}
