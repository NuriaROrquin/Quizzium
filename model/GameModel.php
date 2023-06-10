<?php

class GameModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    private function randomQuestionIDs($id_cuenta)
    {
        $sql = "SELECT p.`id_pregunta` FROM `pregunta` p 
                WHERE id_pregunta NOT IN    (SELECT DISTINCT id_pregunta
                                            FROM respuesta
                                            WHERE id_cuenta =" .$id_cuenta .")
                ORDER BY RAND() LIMIT 1;";
        return $this->database->querySelectAll($sql);
    }

    private function bringQuestions($idString)
    {
        $sql = "SELECT p.`pregunta`, o.`opcion`, o.`es_correcta`, p.`id_pregunta`, p.`id_categoria` FROM `pregunta` p JOIN `opcion` o
        ON p.`id_pregunta` = o.`id_pregunta` WHERE p.`id_pregunta` = " . $idString . ";";

        $questions = $this->database->query($sql);

        $fila = $questions->fetch_all(MYSQLI_ASSOC);

        $question['id_question'] = $fila[0]['id_pregunta'];
        $question['question'] = $fila[0]['pregunta'];
        $question['opcion1'] = $fila[0]['opcion'];
        $question['opcion2'] = $fila[1]['opcion'];
        $question['opcion3'] = $fila[2]['opcion'];
        $question['opcion4'] = $fila[3]['opcion'];
        $question['categoria'] = $fila[0]['id_categoria'];

        foreach ($fila as $clave => $respuesta) {
            if ($respuesta['es_correcta'] == 1) {
                $question['es_correcta'] = $clave + 1;
                $question['textoOpcionCorrecta'] = $respuesta['opcion'];
                break;
            }
        }
        return $question;
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

    public function verificateAnswer($selectedAnswer , $correctOpcion){

        $result = false;

        if($selectedAnswer == $correctOpcion){
            $result = true;
        }
        return $result;
    }

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
}



