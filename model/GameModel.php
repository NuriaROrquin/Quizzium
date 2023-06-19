<?php

class GameModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    private function updateUserAnswersQuantity($id_cuenta, $isCorrect){

        if($isCorrect){
            $sql = "UPDATE cuenta SET `cantidad_respuestas` = `cantidad_respuestas` + 1, `cantidad_correctas` = `cantidad_correctas` + 1 WHERE id_cuenta = " . $id_cuenta  .";";
        }else{
            $sql = "UPDATE cuenta SET `cantidad_respuestas` = `cantidad_respuestas` + 1  WHERE id_cuenta = " . $id_cuenta  .";";
        }
        $this->database->query($sql);

        $this->updateUserDifficultty($id_cuenta);
    }

    private function updateUserDifficultty($id_cuenta){
        $sql = "SELECT `cantidad_respuestas` AS cantRtas, `cantidad_correctas` AS cantCorrectas FROM `cuenta` WHERE id_cuenta =" . $id_cuenta  .";";

        $cantRtas = $this->database->querySelectAssoc($sql)['cantRtas'];
        $cantCorrectas = $this->database->querySelectAssoc($sql)['cantCorrectas'];
        $dificultad = ($cantCorrectas * 100)/ $cantRtas;

        $sql = "UPDATE cuenta SET `dificultad` = " . $dificultad ." WHERE id_cuenta = " . $id_cuenta  .";";

        $this->database->query($sql);

    }

    private function updateQuestionQuantity($id_pregunta, $isCorrect){

        if(!$isCorrect){
            $sql = "UPDATE pregunta SET `veces_respondida` = `veces_respondida` + 1, `veces_inorrecta` = `veces_incorrecta` + 1 WHERE id_pregunta = " . $id_pregunta  .";";
        }else{
            $sql = "UPDATE pregunta SET `veces_respondida` = `veces_respondida` + 1  WHERE id_pregunta = " . $id_pregunta  .";";
        }
        $this->database->query($sql);

        $this->updateQuestionDifficultty($id_pregunta);
    }

    private function updateQuestionDifficultty($id_pregunta){
        $sql = "SELECT `veces_respondida` AS cantRtas, `veces_incorrecta` AS cantIncorrectas FROM `pregunta` WHERE id_pregunta =" . $id_pregunta  .";";

        $cantRtas = $this->database->querySelectAssoc($sql)['cantRtas'];
        $cantIncorrectas = $this->database->querySelectAssoc($sql)['cantIncorrectas'];
        $dificultad = ($cantIncorrectas * 100)/ $cantRtas;

        $sql = "UPDATE pregunta SET `dificultad` = " . $dificultad ." WHERE id_pregunta = " . $id_pregunta  .";";

        $this->database->query($sql);

    }

    private function getUserDifficulty($id_cuenta){
        $sql = "SELECT dificultad FROM `cuenta` WHERE id_cuenta =" . $id_cuenta  .";";

        $dificultad = $this->database->querySelectAssoc($sql)['dificultad'];

        return $dificultad;
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

    private function randomQuestionIDs($id_cuenta, $dificultadUsuario)
    {
        if($dificultadUsuario < 70){
            $sql = "SELECT p.`id_pregunta` FROM `pregunta` p 
                WHERE id_pregunta NOT IN    (SELECT DISTINCT id_pregunta
                                            FROM respuesta
                                            WHERE id_cuenta =" .$id_cuenta .")
                AND dificultad < 70
                ORDER BY RAND() LIMIT 1;";
        }else{
            $sql = "SELECT p.`id_pregunta` FROM `pregunta` p 
                WHERE id_pregunta NOT IN    (SELECT DISTINCT id_pregunta
                                            FROM respuesta
                                            WHERE id_cuenta =" .$id_cuenta .")
                AND dificultad > 70
                ORDER BY RAND() LIMIT 1;";
        }

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
        $dificultadUsuario = $this->getUserDifficulty($id_cuenta);

        $questionID= $this->randomQuestionIDs($id_cuenta, $dificultadUsuario);

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
        return $color;
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

        $this->updateUserAnswersQuantity($id_cuenta, $isCorrect);
        $this->updateQuestionQuantity($id_pregunta, $isCorrect);

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

    public function addQuestionReported($data,$userID){
        $result = false;
        $idQuestion = $data['idQuestion'];
        $reportText = $data['reportText'];

        $sql = "INSERT INTO `reporte`( `id_cuenta`, `id_pregunta`, `descripcion`) VALUES (" .$userID ." , "  .$idQuestion ." , '$reportText');";

        var_dump($sql);
        if($this->database->query($sql)){
            $result = true;
        }

        return $result;

    }
}



