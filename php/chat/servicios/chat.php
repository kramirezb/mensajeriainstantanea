<?php

require_once('conexion_db.php');

class ChatPersonal{

    private $db;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
    }


    public function GetLastMessage($id_emisor, $id_receptor)
    {

        $query = "SELECT contenido FROM mensajes WHERE (usr_emisor_id = 14 OR usr_receptor_id = 1) OR (usr_emisor_id = 1 OR usr_receptor_id = 14) ORDER BY id DESC LIMIT 1";

    }

    public function getAllChatContacts($user_id)
    {
        $query = "SELECT
                        u_amigo.id AS usr_id,
                        u_amigo.nombre_completo AS nombre_contacto,
                        c.numero_telefonico,
                        ultimo_mensaje.contenido AS ultimo_msg,
                        ultimo_mensaje.fecha AS fecha_ultimo_mensaje
                    FROM contactos c
                    JOIN usuarios u_amigo ON c.usuario_amigo_id = u_amigo.id
                    LEFT JOIN (
                        SELECT 
                            m1.usr_emisor_id, 
                            m1.usr_receptor_id, 
                            m1.contenido, 
                            m1.fecha
                        FROM mensajes m1
                        JOIN (
                            -- Subconsulta para obtener el último mensaje entre cada par de usuarios
                            SELECT 
                                LEAST(usr_emisor_id, usr_receptor_id) AS usr1, 
                                GREATEST(usr_emisor_id, usr_receptor_id) AS usr2, 
                                MAX(fecha) AS ultima_fecha
                            FROM mensajes
                            GROUP BY usr1, usr2
                        ) m2 
                        ON ((m1.usr_emisor_id = m2.usr1 AND m1.usr_receptor_id = m2.usr2) 
                            OR (m1.usr_emisor_id = m2.usr2 AND m1.usr_receptor_id = m2.usr1))
                        AND m1.fecha = m2.ultima_fecha
                    ) ultimo_mensaje 
                    ON (ultimo_mensaje.usr_emisor_id = c.usuario_id AND ultimo_mensaje.usr_receptor_id = c.usuario_amigo_id)
                    OR (ultimo_mensaje.usr_emisor_id = c.usuario_amigo_id AND ultimo_mensaje.usr_receptor_id = c.usuario_id)
                    WHERE c.usuario_id = $user_id
                    ORDER BY ultimo_mensaje.fecha DESC;";

        return $this->db->getWithQuery($query);
    }

    public function getChatMessage($id_emisor, $id_receptor)
    {
        $query = "  SELECT msg.* FROM mensajes msg
                    LEFT JOIN usuarios usr ON usr.id = msg.usr_emisor_id
                    WHERE (msg.usr_emisor_id = $id_emisor AND msg.usr_receptor_id = $id_receptor) 
                        OR (msg.usr_emisor_id = $id_receptor AND msg.usr_receptor_id = $id_emisor)
                    ORDER BY msg.id; ";

        //echo $query;

        return $this->db->getWithQuery($query);
    }

    public function getContactHeaderInfo($userId)
    {
        return $this->db->get('usuarios', null, ['id' => $userId]);
    }

    public function insertMessage($mensaje, $emisor_id, $receptor_id){
        $data = [
            'usr_emisor_id' => $emisor_id,
            'usr_receptor_id' => $receptor_id,
            'contenido' => $mensaje,
            'fecha' => date("Y-m-d H:i:s")
        ];

        $this->db->insert('mensajes', $data);
    }


}



?>