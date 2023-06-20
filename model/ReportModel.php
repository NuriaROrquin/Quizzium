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
        $sql = "SELECT p.id_pregunta, p.id_categoria, p.fecha_creacion, p.pregunta, r.descripcion, r.id_reporte
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

            $querySuggestion = "UPDATE `reporte` SET `fue_visto`= 1 WHERE `id_pregunta` = '$idQuestion';";
            $this->database->query($querySuggestion);

            foreach ($answers as $index => $answer) {
                $isCorrect = ($correctAnswer == $index + 1) ? 1 : 0;
                $opcionId = $idAnswers[$index];
                $query = "UPDATE `opcion` SET `id_pregunta` = '$idQuestion', `opcion` = '$answer', `es_correcta` = '$isCorrect' WHERE `id_opcion` = '$opcionId'";
                $this->database->queryWithID($query);
            }

            $database['bd-success'] = "Se modificó la pregunta.";

        } catch (Exception $e) {
            $this->database->rollback();
            $database['bd-error'] = "Ocurrió un error durante las inserciones: " . $e->getMessage();
        }

        return $database;
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

    public function deleteQuestion($question)
    {
        $idQuestion = $question['id'];

        try {

            $querySuggestion = "UPDATE `reporte` SET `fue_visto`= 1 WHERE `id_pregunta` = '$idQuestion';";
            $this->database->query($querySuggestion);

            $queryQuestion = "UPDATE `pregunta` SET `esta_activa` = 0 WHERE `id_pregunta` = '$idQuestion';";
            $this->database->query($queryQuestion);

            $database['bd-success'] = "Se eliminó la pregunta.";
        } catch (Exception $e) {
            $this->database->rollback();
            $database['bd-error'] = "Ocurrió un error durante las inserciones: " . $e->getMessage();
        }

        return $database;
    }

    public function ignoreReport($question){
        $idReport = $question['id_report'];
        $idQuestion = $question['id_question'];

        try {

            $querySuggestion = "UPDATE `reporte` SET `fue_visto`= 1 WHERE `id_pregunta` = '$idQuestion';";
            $this->database->query($querySuggestion);

            $database['bd-success'] = "Se ignoró el reporte.";
        } catch (Exception $e) {
            $this->database->rollback();
            $database['bd-error'] = "Ocurrió un error durante las inserciones: " . $e->getMessage();
        }

        return $database;
    }
}