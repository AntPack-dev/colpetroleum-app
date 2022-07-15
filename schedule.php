<?php
include('./db/ConnectDB.php');

// notificación de programación

//frequency_type
global $mysqli;

$result = $mysqli->query("SELECT *
     , DATEDIFF(DATE_ADD(DATE_FORMAT(frequency_value_date, CONCAT(YEAR(CURRENT_DATE()), '-%m-%d')), INTERVAL (period_diff(date_format(now(), '%Y%m'), date_format(DATE_FORMAT(frequency_value_date, CONCAT(YEAR(CURRENT_DATE()), '-%m-%d')), '%Y%m'))) month), CURRENT_DATE) as next_date 
from inspection_of_mant_teams 
inner join teams_units_rsu on inspection_of_mant_teams.fk_teams_units = teams_units_rsu.id_teams_units
where frequency_type = 2
and DATEDIFF(DATE_ADD(DATE_FORMAT(frequency_value_date, CONCAT(YEAR(CURRENT_DATE()), '-%m-%d')), INTERVAL (period_diff(date_format(now(), '%Y%m'), date_format(DATE_FORMAT(frequency_value_date, CONCAT(YEAR(CURRENT_DATE()), '-%m-%d')), '%Y%m'))) month), CURRENT_DATE) in (7, 0);");

if ($result->num_rows > 0) {
    $mantenimientos = [];
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $mantenimientos[] = $row;
    }
    $result = $mysqli->query('select first_name, second_name, email_user from asign_permits inner join users on users.id_user = asign_permits.user_id_asign where id_module_permit = 1 group by user_id_asign');
    if ($result->num_rows > 0) {
        $usersEmails = [];
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $usersEmails[] = $row;
        }

        require_once './bookstores/PHPMailer/PHPMailerAutoload.php';
        $contenidoTabla = '';
        foreach ($mantenimientos as $mantenimiento) {
            $tr = '<tr>';
            $tr .= '<td style="border: 1px solid black;">' . $mantenimiento['letter_units_teams'] . ' con placa '. $mantenimiento['plate_teams_units'] . '</td>';
            $tr .= '<td style="border: 1px solid black;">' . ($mantenimiento['next_date'] == 0 ? 'Urgente' : '') . '</td>';
            $tr .= '<td style="border: 1px solid black;">' . $mantenimiento['next_date'] . '</td>';
            $tr .= '<td style="border: 1px solid black;">' . $mantenimiento['maintenance_carried'] . '</td>';
            $tr .= '</tr>';
            $contenidoTabla .= $tr;
        }
        $template = file_get_contents('./report/view/notificacionMantenimiento.php');
        $template = str_replace("{{contanidoTabla}}", $contenidoTabla, $template);

        $path = 'images/LOGO.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $template = str_replace("{{logoColpetroleum}}", $base64, $template);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.mailtrap.io';
        $mail->Port = 2525;
        $mail->Username = '1355a47bcecae7';
        $mail->Password = '515048fe7327d4';

        $mail->setFrom('cpsmtto@colpetroleumservices.com', 'CPS MTTO');
        $mail->addAddress($usersEmails[0]['email_user'], $usersEmails[0]['first_name'] . ' ' . $usersEmails[0]['second_name']);
        foreach ($usersEmails as $key => $usersEmail) {
            if ($key > 0) {
                $mail->addCC($usersEmail['email_user'], $usersEmail['first_name'] . ' ' . $usersEmail['second_name']);
            }
        }
        $mail->wordwrap = 50;
        $mail->Subject = 'Recordatorio de Mantenimiento';
        $mail->Body = $template;
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        echo 'Correo enviado: ' . $mail->send();
    }

} else {
    echo "No hay notificaciones para mandar";
}
$mysqli->close();
