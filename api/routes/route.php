<?php

$routesArray = explode("/", $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);

/*=============================================
Cuando no se hace ninguna petición a la API
=============================================*/
if (count($routesArray) == 0) {
    $json = array(
        'status' => 404,
        "results" => "Not found"
    );

    echo json_encode($json, http_response_code($json["status"]));
    return;
} else {

    /*=============================================
	Peticiones GET
	=============================================*/
    if (
        count($routesArray) == 1 &&
        isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "GET"
    ) {

        /*=============================================
		Peticiones GET con filtro
		=============================================*/
        if (isset($_GET["linkTo"]) && isset($_GET["equalTo"])) {

            $response = new GetController();
            $response->getFilterData(explode("?", $routesArray[1])[0], $_GET["linkTo"], $_GET["equalTo"]);

            /*=============================================
		    Peticiones GET entre tablas relacionadas sin filtro
		    =============================================*/
        } else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?", $routesArray[1])[0] == "relations") {
            $response = new GetController();
            $response->getRelData($_GET["rel"], $_GET["type"]);
        } else {

            /*=============================================
		    Peticiones GET sin filtro
		    =============================================*/


            $response = new GetController();
            $response->getData($routesArray[1]);
        }
    }

    /*=============================================
	Peticiones POST
	=============================================*/
    if (
        count($routesArray) == 1 &&
        isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "POST"
    ) {
        $json = array(
            'status' => 200,
            "results" => "POST"
        );

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /*=============================================
	Peticiones PUT
	=============================================*/
    if (
        count($routesArray) == 1 &&
        isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "PUT"
    ) {
        $json = array(
            'status' => 200,
            "results" => "PUT"
        );

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }

    /*=============================================
	Peticiones DELETE
	=============================================*/
    if (
        count($routesArray) == 1 &&
        isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "DELETE"
    ) {
        $json = array(
            'status' => 200,
            "results" => "DELETE"
        );

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }
}
