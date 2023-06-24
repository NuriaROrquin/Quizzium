<?php

class FactoryModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function validate($fields)
    {
        $errors = $this->validateEmptyFields($fields);

        return $errors;
    }

    public function validateEmptyFields($fields)
    {
        $fieldsEmpty = [];

        if (empty($fields['category'])) {
            $fieldsEmpty['category'] = true;
        }

        if (empty($fields['title'])) {
            $fieldsEmpty['title'] = true;
        }

        if (empty($fields['answerOne'])) {
            $fieldsEmpty['answerOne'] = true;
        }

        if (empty($fields['answerTwo'])) {
            $fieldsEmpty['answerTwo'] = true;
        }

        if (empty($fields['answerThree'])) {
            $fieldsEmpty['answerThree'] = true;
        }

        if (empty($fields['answerFour'])) {
            $fieldsEmpty['answerFour'] = true;
        }

        if (empty($fields['correctAnswer'])) {
            $fieldsEmpty['correctAnswer'] = true;
        }

        return $fieldsEmpty;
    }

    public function getRole($id_cuenta){
        $sql = "SELECT `id_rol`FROM `cuenta` WHERE `id_cuenta` = '$id_cuenta';";

        return $this->database->querySelectAssoc($sql)['id_rol'];

    }

    public function sendQuestion($question, $id_cuenta, $id_rol)
    {
        $id_categoria = $question['category'];
        $esta_activa = 0;
        $fue_visto = 0;
        if($id_rol==2){
            $esta_activa = 1;
            $fue_visto = 1;
        }
        $pregunta = $question['title'];
        $answers = [
            $question['answerOne'],
            $question['answerTwo'],
            $question['answerThree'],
            $question['answerFour']
        ];
        $correctAnswer = $question['correctAnswer'];


        try {
            $queryQuestion = "INSERT INTO `pregunta`(`id_categoria`, `esta_activa`, `pregunta`) VALUES ('$id_categoria', '$esta_activa', '$pregunta')";

            $id_pregunta = $this->database->queryWithID($queryQuestion);

            $querySuggestion = "INSERT INTO `sugerencia`(`id_cuenta`, `id_pregunta`, `fue_visto`) VALUES ('$id_cuenta','$id_pregunta', $fue_visto)";
            $this->database->query($querySuggestion);

            foreach ($answers as $index => $answer) {
                $isCorrect = ($correctAnswer == $index + 1) ? 1 : 0;
                $query = "INSERT INTO `opcion`(`id_pregunta`, `opcion`, `es_correcta`) VALUES ('$id_pregunta', '$answer', '$isCorrect')";
                $this->database->query($query);
            }

            $database['bd-success'] = "Se mandó la sugerencia de la pregunta.";
        } catch (Exception $e) {
            $this->database->rollback();
            $database['bd-error'] = "Ocurrió un error durante las inserciones: " . $e->getMessage();
        }
        return $database;
    }

    public function getPendingQuestions()
    {
        $sql = "SELECT p.id_pregunta, p.id_categoria, p.fecha_creacion, p.pregunta 
                FROM `pregunta` p 
                INNER JOIN sugerencia s 
                ON p.id_pregunta = s.id_pregunta 
                AND s.fue_visto = 0";

        $question = $this->database->query($sql);

        $fila = $question->fetch_all(MYSQLI_ASSOC);

        return $fila;
    }

    public function getInfoPendingQuestions($id)
    {
        $sql = "SELECT * FROM `pregunta` JOIN `opcion` ON opcion.id_pregunta = pregunta.id_pregunta WHERE pregunta.esta_activa = 0 AND pregunta.id_pregunta = '" . $id . "';";

        $question = $this->database->query($sql);

        $fila = $question->fetch_all(MYSQLI_ASSOC);

        return $fila;
    }

    public function updateQuestion($question)
    {
        $id_categoria = $question['category'];
        $esta_activa = 1;
        $pregunta = $question['title'];
        $answers = [
            $question['answerOne'],
            $question['answerTwo'],
            $question['answerThree'],
            $question['answerFour']
        ];
        $idAnswers = [
            $question['idAnswerOne'],
            $question['idAnswerTwo'],
            $question['idAnswerThree'],
            $question['idAnswerFour']
        ];
        $correctAnswer = $question['correctAnswer'];
        $idQuestion = $question['id'];

        try {
            $queryQuestion = "UPDATE `pregunta` SET `id_categoria` = '$id_categoria', `esta_activa` = '$esta_activa', `pregunta` = '$pregunta' WHERE `id_pregunta` = '$idQuestion';";
            $this->database->query($queryQuestion);

            $querySuggestion = "UPDATE `sugerencia` SET `fue_visto`= 1 WHERE `id_pregunta` = '$idQuestion';";
            $this->database->query($querySuggestion);

            foreach ($answers as $index => $answer) {
                $isCorrect = ($correctAnswer == $index + 1) ? 1 : 0;
                Logger::info($idAnswers[0]);
                $opcionId = $idAnswers[$index];
                $query = "UPDATE `opcion` SET `id_pregunta` = '$idQuestion', `opcion` = '$answer', `es_correcta` = '$isCorrect' WHERE `id_opcion` = '$opcionId'";
                $this->database->queryWithID($query);
            }

            $database['bd-success'] = "Se aceptó la pregunta.";

        } catch (Exception $e) {
            $this->database->rollback();
            $database['bd-error'] = "Ocurrió un error durante las inserciones: " . $e->getMessage();
        }

        return $database;
    }

    public function deleteQuestion($question)
    {
        $idQuestion = $question['id'];

        try {

            $querySuggestion = "UPDATE `sugerencia` SET `fue_visto`= 1 WHERE `id_pregunta` = '$idQuestion';";
            $this->database->query($querySuggestion);

            /*$queryQuestion = "DELETE FROM `opcion` WHERE `id_pregunta` = '$idQuestion'";
            $this->database->query($queryQuestion);

            $queryQuestion = "DELETE FROM `pregunta` WHERE `id_pregunta` = '$idQuestion'";
            $this->database->query($queryQuestion);*/

            $database['bd-success'] = "Se rechazó la pregunta.";
        } catch (Exception $e) {
            $this->database->rollback();
            $database['bd-error'] = "Ocurrió un error durante las inserciones: " . $e->getMessage();
        }

        return $database;
    }

}



















