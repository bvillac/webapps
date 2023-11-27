<?php
require_once("Models/CentroAtencionModel.php");
require_once("Models/SalonModel.php");
require_once("Models/InstructorModel.php");
require_once("Models/ActividadModel.php");
require_once("Models/ValoracionModel.php");
require_once("Models/NivelModel.php");
class Asistencia extends Controllers
{
    public function __construct()
    {
        sessionStart();
        parent::__construct();
        if (empty($_SESSION['loginEstado'])) {
            header('Location: ' . base_url() . '/login');
            die();
        }
        getPermisos();
    }


    public function asistencia()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header("Location:" . base_url() . '/dashboard');
        }
        $data['page_tag'] = "Asistencia";
        $data['page_name'] = "Asistencia";
        $data['page_title'] = "Asistencia <small> " . TITULO_EMPRESA . "</small>";
        $this->views->getView($this, "asistencia", $data);
    }








}
