<?php

require('third-party/fpdf/fpdf.php');

class StatisticsModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPlayersByCountry($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        //ver el tema de la query de pais, porque ahora lo deberiamos sacar por latitud y longitud

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "SELECT pais, COUNT(*) AS cantidad_usuarios FROM cuenta" . $whereClause . " GROUP BY pais;";

        $result = $this->database->querySelectAll($sql);

        $resultConverted = $this->convertToInt($result);

        $chart['datos'] = $resultConverted;

        $chart['columnas'] = [
            ['string', 'Pais'],
            ['number', 'Total']
        ];

        $chart['title'] = 'Cantidad de usuarios por pais';

        return $chart;
    }

    public function getPlayersByAge($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "SELECT 
        CASE 
        WHEN edad_calculada < 18 THEN 'Menores'
        WHEN edad_calculada >= 18 AND edad_calculada <= 65 THEN 'Medio'
        ELSE 'Jubilados'
        END AS grupo_edad,
        COUNT(*) AS cantidad_usuarios
        FROM cuenta
        " . $whereClause . "
        GROUP BY grupo_edad;";

        $result = $this->database->querySelectAll($sql);

        $resultConverted = $this->convertToInt($result);

        $chart['datos'] = $resultConverted;

        $chart['columnas'] = [
            ['string', 'Pais'],
            ['number', 'Total']
        ];

        $chart['title'] = 'Cantidad de usuarios por edad';

        return $chart;
    }

    public function getPlayersByGender($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "SELECT 
        CASE
        WHEN id_genero = 1 THEN 'Masculino'
        WHEN id_genero = 2 THEN 'Femenino'
        WHEN id_genero = 3 THEN 'Prefiero no decirlo'
        ELSE 'Desconocido'
        END AS grupo_genero,
        COUNT(*) AS cantidad_usuarios
        FROM cuenta
        " . $whereClause . "
        GROUP BY grupo_genero;";

        $result = $this->database->querySelectAll($sql);

        $resultConverted = $this->convertToInt($result);

        $chart['datos'] = $resultConverted;

        $chart['columnas'] = [
            ['string', 'Pais'],
            ['number', 'Total']
        ];

        $chart['title'] = 'Cantidad de usuarios por género';

        return $chart;
    }

    public function getPlayers($filters)
    {

        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_cuenta) AS cantidad_de_jugadores
        FROM cuenta " . $whereClause . ";";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_jugadores'];
    }

    public function getNumberOfGames($filters)
    {

        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE partida.fecha_partida >= '" . $dateFrom . "' AND partida.fecha_partida <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_juego) AS cantidad_de_partidas
        FROM juego JOIN partida ON juego.id_partida = partida.id_partida " . $whereClause . ";";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_partidas'];
    }

    public function getNumberOfActiveQuestions($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " AND fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_pregunta) AS cantidad_de_preguntas_activas
        FROM pregunta 
        WHERE esta_activa = 1 " . $whereClause . " ;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_preguntas_activas'];
    }

    public function getNumberOfViwedSuggestions($filters)
    {

        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " AND fecha_sugerencia >= '" . $dateFrom . "' AND fecha_sugerencia <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_sugerencia) AS cantidad_de_sugerencias_vistas
        FROM sugerencia
        WHERE fue_visto = 1 " . $whereClause . " ;";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_sugerencias_vistas'];
    }

    public function getNumberOfTotalSuggestions($filters)
    {

        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_sugerencia >= '" . $dateFrom . "' AND fecha_sugerencia <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT COUNT(id_sugerencia) AS cantidad_de_sugerencias
        FROM sugerencia " . $whereClause . ";";

        $result = $this->database->querySelectAssoc($sql);

        return $result['cantidad_de_sugerencias'];
    }

    public function getPercentageOfEffectivenessPerUser($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "
        SELECT id_cuenta , usuario , dificultad
        FROM cuenta " . $whereClause . ";";

        $result = $this->database->querySelectAll($sql);

        $usersData = array();

        foreach ($result as $row) {

            $userData = array(
                'id_cuenta' => $row[0],
                'usuario' => $row[1],
                'dificultad' => $row[2]
            );

            $usersData[] = $userData;
        }


        return $usersData;
    }

    public function getNewUsers($filters)
    {
        $whereClause = '';
        $dateFrom = $filters['dateFrom'];
        $dateTo = $filters['dateTo'];

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE fecha_creacion >= '" . $dateFrom . "' AND fecha_creacion <= '" . $dateTo . "'";
        }

        $sql = "SELECT id_cuenta, usuario, fecha_creacion  FROM cuenta " . $whereClause . ";";

        $result = $this->database->querySelectAll($sql);

        $usersData = array();

        foreach ($result as $row) {

            $userData = array(
                'id_cuenta' => $row[0],
                'usuario' => $row[1],
                'fecha_creacion' => $row[2]
            );
            $usersData[] = $userData;
        }

        return $usersData;
    }

    public function getPDF($data)
    {

        $pdf = new FPDF();
        $pdf->AddPage("P", "A3");
        $pdf->SetFont('Arial', 'B', 14);
        $pageWidth = $pdf->GetPageWidth();

        $pdf->Cell(40, 10, "Fechas:");
        $pdf->Cell(40, 10, $data['dateFrom'], 1, 0, 'C');
        $pdf->Cell(10, 10, " - ", 1, 0, 'C');
        $pdf->Cell(40, 10, $data['dateTo'], 1, 1, 'C');

        $pdf->Cell(0, 20, "", 0, 1, "C");

        $pdf->Cell($pageWidth / 2, 10, "Total de jugadores", 1, 0, 'C');
        $pdf->Cell($pageWidth / 3, 10, $data['total_players'], 1, 1, 'C');

        $pdf->Cell($pageWidth / 2, 10, "Total de partidas", 1, 0, 'C');
        $pdf->Cell($pageWidth / 3, 10, $data['total_games'], 1, 1, 'C');

        $pdf->Cell($pageWidth / 2, 10, "Preguntas activas", 1, 0, 'C');
        $pdf->Cell($pageWidth / 3, 10, $data['total_questions_active'], 1, 1, 'C');

        $pdf->Cell($pageWidth / 2, 10, "Total de sugerencias", 1, 0, 'C');
        $pdf->Cell($pageWidth / 3, 10, $data['total_suggestions'], 1, 1, 'C');

        $pdf->Cell($pageWidth / 2, 10, "Total de sugerencias vistas", 1, 0, 'C');
        $pdf->Cell($pageWidth / 3, 10, $data['total_viwed_suggestions'], 1, 1, 'C');


        $pdf->Cell(0, 20, "", 0, 1, "C");
        $pdf->Cell(40, 10, "Efectividad por usuario:");
        $pdf->Cell(0, 20, "", 0, 1, "C");

        $usersDifficulty = $this->generateArray($data['percentage_effective_for_player']);
        foreach ($usersDifficulty as $entry) {
            $pdf->Cell($pageWidth / 4, 10, "ID Cuenta: " . $entry['id_cuenta'], 1, 0, 'L');
            $pdf->Cell($pageWidth / 4, 10, "Usuario: " . $entry['Usuario'], 1, 0, 'L');
            $pdf->Cell($pageWidth / 4, 10, "Dificultad: " . $entry['dificultad'], 1, 1, 'L');
        }


        $pdf->Cell(0, 20, "", 0, 1, "C");
        $pdf->Cell(40, 10, "Cantidad de usuarios nuevos:");
        $pdf->Cell(0, 20, "", 0, 1, "C");

        $newUsers = $this->generateArray($data['total_new_users']);
        foreach ($newUsers as $entry) {
            $pdf->Cell($pageWidth / 4, 10, "ID Cuenta: " . $entry['id_cuenta'], 1, 0, 'L');
            $pdf->Cell($pageWidth / 4, 10, "Usuario: " . $entry['Usuario'], 1, 0, 'L');
            $pdf->Cell($pageWidth / 4, 10, "Creada: " . $entry['fecha de creacion'], 1, 1, 'L');
        }


        $pdf->Cell(0, 20, "", 0, 1, "C");
        $pdf->Cell(40, 10, "Cantidad de usuarios por país:");
        $pdf->Cell(0, 20, "", 0, 1, "C");


        $lines = explode("\n", $data['by_country']);

        foreach ($lines as $line) {
            $pdf->Cell($pageWidth / 4, 10, $line, 1, 1, 'L');
        }


        $this->saveChartImage($data, $pdf);


        $pdf->Output();
    }

    private function generateArray($string)
    {

        $rows = explode("\n", $string); // Separar por saltos de línea

        $data = array();

        foreach ($rows as $row) {
            $fields = explode(" - ", $row); // Separar cada campo en la línea por el delimitador " - "

            $entry = array();

            foreach ($fields as $field) {
                list($key, $value) = explode(": ", $field); // Separar la clave y el valor por el delimitador ": "

                $entry[$key] = $value;
            }

            $data[] = $entry;
        }

        return $data;

    }

    private function convertToInt($result)
    {
        foreach ($result as &$dato) {
            $dato[1] = intval($dato[1]);
        }

        return $result;
    }

    private function saveChartImage($data, $pdf)
    {
        $imageData = $data['image'];
        $decodedImage = base64_decode(substr($imageData, strpos($imageData, ',') + 1));

        $imagePath = "./public/charts/img.png";

        file_put_contents($imagePath, $decodedImage);

        $x = 10;
        $y = 10;
        $width = 50;
        $height = 50;

        $pdf->Image($imagePath, $x, $y, $width, $height);

        unlink($imagePath);

    }
}