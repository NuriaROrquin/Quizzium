<?php

class GameModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    private function userDifficulty($id_cuenta){

        $sql = "SELECT COUNT(`id_respuesta`) AS cantRtas FROM `respuesta` WHERE id_cuenta =" . $id_cuenta  .";";

        $totalPreguntasRespondidas = $this->database->querySelectAssoc($sql)['cantRtas'];

        $sql = "SELECT COUNT(`id_respuesta`) AS cantRtas FROM `respuesta` WHERE id_cuenta =" . $id_cuenta  . " AND fue_correcta = 1;";

        $totalPreguntasCorrectas = $this->database->querySelectAssoc($sql)['cantRtas'];

        $dificultad = ( $totalPreguntasCorrectas * 100 ) / $totalPreguntasRespondidas;

        if ( $dificultad > 70 ) {
            echo "usuario experto";
        } else{
            echo "usuario principiante";
        }

        var_dump($dificultad);
        exit();
    }

    private function questionDifficulty($id_pregunta){

        $sql = "SELECT COUNT(`id_respuesta`) AS cantRtas FROM `respuesta` WHERE id_pregunta =" . $id_pregunta  .";";

        $totalPreguntas = $this->database->querySelectAssoc($sql)['cantRtas'];

        $sql = "SELECT COUNT(`id_respuesta`) AS cantRtas FROM `respuesta` WHERE id_pregunta =" . $id_pregunta  . " AND fue_correcta = 1;";

        $totalPreguntasCorrectas = $this->database->querySelectAssoc($sql)['cantRtas'];

        $dificultad = ( $totalPreguntasCorrectas * 100 ) / $totalPreguntas;

        if ( $dificultad > 70 ) {
            echo "pregunta facil";
        } else{
            echo "pregunta dificil";
        }

        var_dump($dificultad);
        exit();
    }


    private function randomQuestionIDs($id_cuenta)
    {

        //FALTA HACER ESTO ********************************************************
        //$this->questionDifficulty($id_cuenta);

        //$this->userDifficulty($id_cuenta);

        $sql = "SELECT p.`id_pregunta` FROM `pregunta` p 
                WHERE id_pregunta NOT IN    (SELECT DISTINCT id_pregunta
                                            FROM respuesta
                                            WHERE id_cuenta =" .$id_cuenta .")
                ORDER BY RAND() LIMIT 1;";

        $result = $this->database->querySelectAll($sql);

        if($result == null){

            $this->resetQuestions($id_cuenta);

            $result = $this->database->querySelectAll($sql);

        }
        return $result;
    }

    private function resetQuestions($id_cuenta){

        $sql = "DELETE FROM `respuesta` 
                WHERE id_cuenta =" . $id_cuenta .";";

        $this->database->query($sql);
    }

    private function bringQuestions($idString)
    {


        $sql = "SELECT p.`pregunta`, o.`opcion`, o.`es_correcta`, p.`id_pregunta`, o.`id_opcion` , p.`id_categoria` FROM `pregunta` p JOIN `opcion` o
        ON p.`id_pregunta` = o.`id_pregunta` WHERE p.`id_pregunta` = " . $idString . ";";

        $question = $this->database->query($sql);

        $fila = $question->fetch_all(MYSQLI_ASSOC);


        $dataQuestion['id_question'] = $fila[0]['id_pregunta'];

        $dataQuestion['question'] = $fila[0]['pregunta'];

        $dataQuestion['opcion1'] = $fila[0]['opcion'];
        $dataQuestion['opcion2'] = $fila[1]['opcion'];
        $dataQuestion['opcion3'] = $fila[2]['opcion'];
        $dataQuestion['opcion4'] = $fila[3]['opcion'];

        $dataQuestion['id_opcion1'] = $fila[0]['id_opcion'];
        $dataQuestion['id_opcion2'] = $fila[1]['id_opcion'];
        $dataQuestion['id_opcion3'] = $fila[2]['id_opcion'];
        $dataQuestion['id_opcion4'] = $fila[3]['id_opcion'];

        $dataQuestion['categoria'] = $fila[0]['id_categoria'];

        foreach ($fila as $clave => $respuesta) {
            if ($respuesta['es_correcta'] == 1) {
                $dataQuestion['es_correcta'] = $clave + 1;
                $dataQuestion['textoOpcionCorrecta'] = $respuesta['opcion'];
                break;
            }
        }

        return $dataQuestion;
    }

    public function getQuestion($id_cuenta)
    {
        $questionID= $this->randomQuestionIDs($id_cuenta);

        $questionData = $this->bringQuestions($questionID[0][0]);

        return $questionData;
    }

    public function setCategoryColor($category)
    {
        switch($category){
            case '1':
                $color ='#008639';
                break;
            case '2':
                $color ='#BEA821';
                break;
            case '3':
                $color ='#DC0000';
                break;
            case '4':
                $color ='#0176D2';
                break;
            case '5':
                $color ='#FF69B4';
                break;
            case '6':
                $color ='#FF9400';
                break;

        }
        return 'background-color:'. $color .';';
    }

    public function getCategoryName($id_categoria){
        $result = $this->database->querySelectAssoc("SELECT `nombre_categoria` FROM `categoria` WHERE `id_categoria` = '$id_categoria'");


        return $result['nombre_categoria'];
    }

    public function getUserData($id_cuenta)
    {
       return $this->database->querySelectAssoc("SELECT foto_perfil, usuario FROM cuenta WHERE id_cuenta = '$id_cuenta'");
    }


    public function startGame($id_cuenta)
    {

        $id_partida = $this->database->queryWithID("INSERT INTO `partida` VALUES ();");

        $id_juego = $this->database->queryWithID("INSERT INTO `juego`(`id_partida`, `id_cuenta`) VALUES (" . $id_partida . "," . $id_cuenta . ");");

        return $id_juego;
    }

    /*
    public function verificateAnswer($selectedAnswer , $correctOpcion){

        $result = false;

        if($selectedAnswer == $correctOpcion){
            $result = true;
        }
        return $result;
    }
    */

    public function updateScore($id_juego)
    {

        $this->database->query("UPDATE `juego` SET `puntaje`= puntaje+1 WHERE `id_juego` = " .$id_juego .";");

        $score = $this->database->querySelectAssoc("SELECT `puntaje` FROM `juego` WHERE `id_juego` = " .$id_juego .";");

        return $score['puntaje'];
    }

    public function insertAnswer($isCorrect, $id_cuenta, $id_pregunta)
    {
        if($isCorrect){
            $this->database->query("INSERT INTO `respuesta`(`id_pregunta`, `id_cuenta`, `fue_correcta`) VALUES (".$id_pregunta ."," .$id_cuenta .", 1);");
        }else{
            $this->database->query("INSERT INTO `respuesta`(`id_pregunta`, `id_cuenta`) VALUES (".$id_pregunta ."," .$id_cuenta .");");
        }
    }

    public function verificateAnswer( $id_pregunta, $selectedAnswer ){


        $result = false;

        $sql = "SELECT `id_opcion` FROM `pregunta` AS P LEFT JOIN  `opcion` O ON P.id_pregunta = O.id_pregunta WHERE P.id_pregunta = " . $id_pregunta .
            " AND O.es_correcta = 1;";


        $correctAnswer = $this->database->querySelectAssoc($sql);


        if($correctAnswer['id_opcion'] == $selectedAnswer){
            $result = true;
        }

        return $result;
    }
}



