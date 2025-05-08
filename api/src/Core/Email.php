<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\{Env};


class Email
{
    protected $mailer;

    public function __construct()
    {
        $env = new Env();
        $this->mailer = new PHPMailer(true);
        
        $this->mailer->isSMTP();
        $this->mailer->Host       = $env->get('EMAIL_HOST');
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $env->get('EMAIL_USERNAME');
        $this->mailer->Password   = $env->get('EMAIL_PASS');

        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = $env->get('EMAIL_PORT');
        $this->mailer->isHTML(true); // Configurar para enviar HTML por defecto

        $this->mailer->setFrom(  $env->get('EMAIL_USERNAME') );
    }

    /**
     * Configura el remitente del correo.
     */
    public function setFrom($email, $name = '')
    {
        $this->mailer->setFrom($email, $name);
    }

    /**
     * Añade un destinatario al correo.
     */
    public function addAddress($email, $name = '')
    {
        $this->mailer->addAddress($email, $name);
    }

    /**
     * Añade un correo de respuesta.
     */
    public function addReplyTo($email, $name = '')
    {
        $this->mailer->addReplyTo($email, $name);
    }

    /**
     * Añade un archivo adjunto al correo.
     */
    public function addAttachment($path, $name = '')
    {
        $this->mailer->addAttachment($path, $name);
    }

    /**
     * Envía el correo con el asunto y contenido dados.
     */
    public function send($subject, $body, $altBody = '')
    {
        try {
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->AltBody = $altBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            
            error_log("Error al enviar correo: " . $this->mailer->ErrorInfo);
            
            return false;
        }
    }
}
