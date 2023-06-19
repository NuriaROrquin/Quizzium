<?php

class ReportModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPendingQuestions()
    {
        $sql = "SELECT p.id_pregunta, p.id_categoria, p.fecha_creacion, p.pregunta, r.descripcion 
                FROM `pregunta` p 
                INNER JOIN reporte r 
                ON p.id_pregunta = r.id_pregunta 
                AND r.fue_visto = 0";

        $question = $this->database->query($sql);

        $fila = $question->fetch_all(MYSQLI_ASSOC);

        return $fila;
    }

    public function getInfoPendingQuestions($id)
    {
        $sql = "SELECT * FROM `pregunta` JOIN `opcion` ON opcion.id_pregunta = pregunta.id_pregunta WHERE pregunta.esta_activa = 1 AND pregunta.id_pregunta = '" . $id . "';";

        $question = $this->database->query($sql);

        $fila = $question->fetch_all(MYSQLI_ASSOC);

        return $fila;
    }
}