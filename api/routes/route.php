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
        if (
            isset($_GET["linkTo"]) && isset($_GET["equalTo"]) &&
            !isset($_GET["rel"]) && !isset($_GET["type"])
        ) {

            /*=============================================
			Preguntamos si viene variables de orden
			=============================================*/
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {
                $orderBy = null;
                $orderMode = null;
            }

            /*=============================================
			Preguntamos si viene variables de límite
			=============================================*/
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {
                $startAt = null;
                $endAt = null;
            }

            $response = new GetController();
            $response->getFilterData(explode("?", $routesArray[1])[0], $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);

            /*=================================================
		    Peticiones GET entre tablas relacionadas sin filtro
		    =================================================*/
        } else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?", $routesArray[1])[0] == "relations" && !isset($_GET["linkTo"]) && !isset($_GET["equalTo"])) {

            /*=============================================
			Preguntamos si viene variables de orden
			=============================================*/
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {
                $orderBy = null;
                $orderMode = null;
            }

            /*=============================================
			Preguntamos si viene variables de límite
			=============================================*/
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {
                $startAt = null;
                $endAt = null;
            }

            $response = new GetController();
            $response->getRelData($_GET["rel"], $_GET["type"], $orderBy, $orderMode, $startAt, $endAt);

            /*=============================================
		    Peticiones GET entre tablas relacionadas con filtro
		    =============================================*/
        } else if (isset($_GET["rel"]) && isset($_GET["type"]) && explode("?", $routesArray[1])[0] == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])) {

            /*=============================================
			Preguntamos si viene variables de orden
			=============================================*/
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {
                $orderBy = null;
                $orderMode = null;
            }

            /*=============================================
			Preguntamos si viene variables de límite
			=============================================*/
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {
                $startAt = null;
                $endAt = null;
            }

            $response = new GetController();
            $response->getRelFilterData($_GET["rel"], $_GET["type"], $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);

            /*=============================================
		    Peticiones GET para el buscador
		    =============================================*/
        } else if (isset($_GET["linkTo"]) && isset($_GET["search"])) {

            /*=============================================
			Preguntamos si viene variables de orden
			=============================================*/
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {
                $orderBy = null;
                $orderMode = null;
            }

            /*=============================================
			Preguntamos si viene variables de límite
			=============================================*/
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {
                $startAt = null;
                $endAt = null;
            }

            if (explode("?", $routesArray[1])[0] == "relations" && isset($_GET["rel"]) && isset($_GET["type"])) {
                $response = new GetController();
                $response->getSearchRelData($_GET["rel"], $_GET["type"], $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);
            } else {
                $response = new GetController();
                $response->getSearchData(explode("?", $routesArray[1])[0], $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);
            }

            /*=============================================
		    Peticiones GET sin filtro
		    =============================================*/
        } else {

            /*=============================================
			Preguntamos si viene variables de orden
			=============================================*/
            if (isset($_GET["orderBy"]) && isset($_GET["orderMode"])) {
                $orderBy = $_GET["orderBy"];
                $orderMode = $_GET["orderMode"];
            } else {
                $orderBy = null;
                $orderMode = null;
            }

            /*=============================================
			Preguntamos si viene variables de límite
			=============================================*/
            if (isset($_GET["startAt"]) && isset($_GET["endAt"])) {
                $startAt = $_GET["startAt"];
                $endAt = $_GET["endAt"];
            } else {
                $startAt = null;
                $endAt = null;
            }


            $response = new GetController();
            $response->getData(explode("?", $routesArray[1])[0], $orderBy, $orderMode, $startAt, $endAt);
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

        /*==================================================
		Traemos el listado de columnas de la tabla a cambiar
		==================================================*/
        $columns = array();
        $database = RoutesController::database();
        $response = PostController::getColumnsData(explode("?", $routesArray[1])[0], $database);

        foreach ($response as $key => $value) {
            array_push($columns, $value->item);
        }

        /*=============================================
		Quitamos el primer y ultimo indice
		=============================================*/
        array_shift($columns);
        array_pop($columns);

        /*=============================================
		Recibimos los valores POST
		=============================================*/
        if (isset($_POST)) {

            /*========================================================================================
		    Validamos que las variables PUT coincidan con los nombres de columnas de la base de datos
		    ========================================================================================*/
            $count = 0;

            foreach (array_keys($_POST) as $key => $value) {
                $count = array_search($value, $columns);
            }

            if ($count > 0) {

                /*==========================================================
				Solicitamos respuesta del controlador para registar usuarios
				==========================================================*/
                if (isset($_GET["register"]) && $_GET["register"] == true) {
                    $response = new PostController();
                    $response->postRegister(explode("?", $routesArray[1])[0], $_POST);

                    /*===============================================================
				    Solicitamos respuesta del controlador para el ingreso de usuarios
				    ===============================================================*/
                } else if (isset($_GET["login"]) && $_GET["login"] == true) {
                    $response = new PostController();
                    $response->postLogin(explode("?", $routesArray[1])[0], $_POST);

                    /*=============================================
				    Validamos el token de autenticación
				    =============================================*/
                } else if (isset($_GET["token"])) {

                    /*=============================================
					    Traemos el usuario de acuerdo al token
					    =============================================*/
                    $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null);

                    if (!empty($user)) {

                        /*=============================================
					    Validamos que el token no haya expirado
					    =============================================*/
                        $time = time();

                        if ($user[0]->token_exp_user > $time) {

                            /*=======================================================================
					        Solicitamos respuesta del controlador para crear datos en cualquier tabla
					        =======================================================================*/
                            $response = new PostController();
                            $response->postData(explode("?", $routesArray[1])[0], $_POST);
                        } else {
                            $json = array(
                                'status' => 400,
                                'results' => "Error: The token has expired"
                            );

                            echo json_encode($json, http_response_code($json["status"]));
                            return;
                        }
                    } else {
                        $json = array(
                            'status' => 400,
                            'results' => "Error: The user is not authorized"
                        );

                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                } else {
                    $json = array(
                        'status' => 400,
                        'results' => "Error: Authorization required"
                    );

                    echo json_encode($json, http_response_code($json["status"]));
                    return;
                }
            } else {
                $json = array(
                    'status' => 400,
                    'results' => "Error: Fields in the form do not match the database"
                );

                echo json_encode($json, http_response_code($json["status"]));
                return;
            }
        }
    }

    /*=============================================
	Peticiones PUT
	=============================================*/
    if (
        count($routesArray) == 1 &&
        isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "PUT"
    ) {

        /*=============================================
		Preguntamos si viene ID
		=============================================*/
        if (isset($_GET["id"]) && isset($_GET["nameId"])) {

            /*=============================================
			Validamos que exista el ID
			=============================================*/
            $table = explode("?", $routesArray[1])[0];
            $linkTo = $_GET["nameId"];
            $equalTo = $_GET["id"];
            $orderBy = null;
            $orderMode = null;
            $startAt = null;
            $endAt = null;

            $response = PutController::getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);

            if ($response) {

                /*=============================================
				Capturamos los datos del formulario
				=============================================*/
                $data = array();
                parse_str(file_get_contents('php://input'), $data);

                /*==================================================
				Traemos el listado de columnas de la tabla a cambiar
				==================================================*/
                $columns = array();
                $database = RoutesController::database();
                $response = PostController::getColumnsData(explode("?", $routesArray[1])[0], $database);

                foreach ($response as $key => $value) {
                    array_push($columns, $value->item);
                }

                /*=============================================
				Quitamos el primer y ultimo indice
				=============================================*/
                array_shift($columns);
                array_pop($columns);
                array_pop($columns);

                /*=======================================================================================
				Validamos que las variables de los campos PUT coincidan con los nombres de columnas de la base de datos
				=======================================================================================*/
                $count = 0;

                foreach (array_keys($data) as $key => $value) {
                    $count = array_search($value, $columns);
                }

                if ($count > 0) {

                    if (isset($_GET["token"])) {

                        /*=============================================
					    Traemos el usuario de acuerdo al token
					    =============================================*/
                        $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null);

                        if (!empty($user)) {

                            /*=============================================
					        Validamos que el token no haya expirado
					        =============================================*/
                            $time = time();

                            if ($user[0]->token_exp_user > $time) {

                                /*===============================================================
			                    Solicitamos respuesta del controlador para editar cualquier tabla
			                    ===============================================================*/
                                $response = new PutController();
                                $response->putData(explode("?", $routesArray[1])[0], $data, $_GET["id"], $_GET["nameId"]);
                            } else {
                                $json = array(
                                    'status' => 400,
                                    'results' => "Error: The token has expired"
                                );

                                echo json_encode($json, http_response_code($json["status"]));
                                return;
                            }
                        } else {
                            $json = array(
                                'status' => 400,
                                'results' => "Error: The user is not authorized"
                            );

                            echo json_encode($json, http_response_code($json["status"]));
                            return;
                        }
                    } else {
                        $json = array(
                            'status' => 400,
                            'results' => "Error: Authorization required"
                        );

                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                } else {
                    $json = array(
                        'status' => 400,
                        'results' => "Error: Fields in the form do not match the database"
                    );

                    echo json_encode($json, http_response_code($json["status"]));
                    return;
                }
            } else {
                $json = array(
                    'status' => 400,
                    'results' => "Error: The id is not found in the database"
                );

                echo json_encode($json, http_response_code($json["status"]));
                return;
            }
        }
    }

    /*=============================================
	Peticiones DELETE
	=============================================*/

    if (
        count($routesArray) == 1 &&
        isset($_SERVER["REQUEST_METHOD"]) &&
        $_SERVER["REQUEST_METHOD"] == "DELETE"
    ) {

        /*=============================================
		Preguntamos si viene ID
		=============================================*/
        if (isset($_GET["id"]) && isset($_GET["nameId"])) {

            /*=============================================
			Validamos que exista el ID
			=============================================*/
            $table = explode("?", $routesArray[1])[0];
            $linkTo = $_GET["nameId"];
            $equalTo = $_GET["id"];
            $orderBy = null;
            $orderMode = null;
            $startAt = null;
            $endAt = null;

            $response = PutController::getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);

            if ($response) {

                if (isset($_GET["token"])) {

                    /*=============================================
                    Traemos el usuario de acuerdo al token
                    =============================================*/
                    $user = GetModel::getFilterData("users", "token_user", $_GET["token"], null, null, null, null);

                    if (!empty($user)) {

                        /*=============================================
					    Validamos que el token no haya expirado
					    =============================================*/
                        $time = time();

                        if ($user[0]->token_exp_user > $time) {

                            /*=============================================
				            Solicitamos respuesta del controlador
				            =============================================*/
                            $response = new DeleteController();
                            $response->deleteData(explode("?", $routesArray[1])[0], $_GET["id"], $_GET["nameId"]);
                        } else {
                            $json = array(
                                'status' => 400,
                                'results' => "Error: The token has expired"
                            );

                            echo json_encode($json, http_response_code($json["status"]));
                            return;
                        }
                    } else {
                        $json = array(
                            'status' => 400,
                            'results' => "Error: The user is not authorized"
                        );

                        echo json_encode($json, http_response_code($json["status"]));
                        return;
                    }
                } else {
                    $json = array(
                        'status' => 400,
                        'results' => "Error: Authorization required"
                    );

                    echo json_encode($json, http_response_code($json["status"]));
                    return;
                }
            } else {
                $json = array(
                    'status' => 400,
                    'results' => "Error: The id is not found in the database"
                );

                echo json_encode($json, http_response_code($json["status"]));
                return;
            }
        }
    }
}
