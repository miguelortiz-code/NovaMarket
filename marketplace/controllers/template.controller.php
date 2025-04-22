<?php

class TemplateController
{
    /*=============================================
	Traemos la Vista Principal de la plantilla
	=============================================*/
    public function index()
    {
        include "views/template.php";
    }

    /*=============================================
	Ruta Principal o Dominio del sitio
	=============================================*/
    static public function path()
    {
        return "http://api.novamarket.com/";
    }
}
