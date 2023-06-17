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

    public function sendQuestion($question)
    {
        $id_categoria = $question['category'];
        $fecha_creacion = date('Y-m-d H:i:s');
        $esta_activa = 0;
        $pregunta = $question['title'];
        $answers = [
            $question['answerOne'],
            $question['answerTwo'],
            $question['answerThree'],
            $question['answerFour']
        ];
        $correctAnswer = $question['correctAnswer'];

        try {
            $queryQuestion = "INSERT INTO `pregunta`(`id_categoria`, `fecha_creacion`, `esta_activa`, `pregunta`) VALUES ('$id_categoria', '$fecha_creacion', '$esta_activa', '$pregunta')";
            $id_pregunta = $this->database->queryWithID($queryQuestion);

            foreach ($answers as $index => $answer) {
                $isCorrect = ($correctAnswer == $index+1) ? 1 : 0;
                $query = "INSERT INTO `opcion`(`id_pregunta`, `opcion`, `es_correcta`) VALUES ('$id_pregunta', '$answer', '$isCorrect')";
                $this->database->queryWithID($query);
            }

            $database['bd-success'] = "Se mandó la sugerencia de la pregunta.";
        } catch (Exception $e) {
            $this->database->rollback();
            $database['bd-error'] = "Ocurrió un error durante las inserciones: " . $e->getMessage();
        }

        return $database;
    }

}



















