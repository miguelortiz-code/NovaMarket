<?php

class GetController
{

    /*=============================================
	Peticiones GET sin filtro
	=============================================*/
    static public function getData($table, $orderBy, $orderMode, $startAt, $endAt)
    {
        $response = GetModel::getData($table, $orderBy, $orderMode, $startAt, $endAt);

        $return = new GetController();
        $return->fncResponse($response, "getData");
    }

    /*=============================================
	Peticiones GET con filtro
	=============================================*/
    static public function getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt)
    {
        $response = GetModel::getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);

        $return = new GetController();
        $return->fncResponse($response, "getFilterData");
    }

    /*=============================================
	Peticiones GET tablas relacionadas sin filtro
	=============================================*/
    static public function getRelData($rel, $type, $orderBy, $orderMode, $startAt, $endAt)
    {
        $response = GetModel::getRelData($rel, $type, $orderBy, $orderMode, $startAt, $endAt);

        $return = new GetController();
        $return->fncResponse($response, "getRelData");
    }

    /*=============================================
	Peticiones GET tablas relacionadas con filtro
	=============================================*/
    static public function getRelFilterData($rel, $type, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt)
    {
        $response = GetModel::getRelFilterData($rel, $type, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);

        $return = new GetController();
        $return->fncResponse($response, "getRelFilterData");
    }

    /*=============================================
	Peticiones GET para el buscador
	=============================================*/
    static public function getSearchData($table, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt)
    {
        $response = GetModel::getSearchData($table, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);

        $return = new GetController();
        $return->fncResponse($response, "getFilterData");
    }

    /*========================================================
	Peticiones GET  para el buscador entre tablas relacionadas
	========================================================*/
    public function getSearchRelData($rel, $type, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt)
    {
        $response = GetModel::getSearchRelData($rel, $type, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);

        $return = new GetController();
        $return->fncResponse($response, "getSearchRelData");
    }

    /*=============================================
	Respuestas del controlador
	=============================================*/
    static public function fncResponse($response, $method)
    {
        if (!empty($response)) {
            $json = array(
                'status' => 200,
                'total' => count($response),
                "results" => $response
            );
        } else {
            $json = array(
                'status' => 404,
                "results" => "Not Found",
                'method' => $method
            );
        }

        echo json_encode($json, http_response_code($json["status"]));
        return;
    }
}
