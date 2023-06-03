<?php
class PlayModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    private function randomQuestionIDs (){
        $sql = "SELECT p.`id_pregunta` FROM `pregunta` p ORDER BY RAND() LIMIT 1;";
        return $this->database->querySelectAll($sql);
    }

    private function bringQuestions ($idString){
        $sql = "SELECT p.`pregunta`, o.`opcion`, o.`es_correcta`, p.`id_pregunta` FROM `pregunta` p JOIN `opcion` o
         ON p.`id_pregunta` = o.`id_pregunta` WHERE p.`id_pregunta` = ". $idString . ";";

        $questions = $this->database->query($sql);

        $fila = $questions->fetch_all(MYSQLI_ASSOC);

        $question['id'] = $fila[0]['id_pregunta'];
        $question['question'] = $fila[0]['pregunta'];
        $question['opcion1'] = $fila[0]['opcion'];
        $question['opcion2'] = $fila[1]['opcion'];
        $question['opcion3'] = $fila[2]['opcion'];
        $question['opcion4'] = $fila[3]['opcion'];

        foreach ( $fila as $clave => $respuesta ) {
            if( $respuesta['es_correcta'] == 1 ){
                $question['opcionCorrecta'] = $clave;
                break;
            }
        }

        return $question;
    }

    public function play ()
    {
        $questionIDs = $this->randomQuestionIDs();

        $question = $this->bringQuestions($questionIDs[0][0]);
    }
}



