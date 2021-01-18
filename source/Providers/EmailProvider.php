<?php

namespace Source\Providers;

use Exception;
use stdClass;
use PHPMailer\PHPMailer\PHPMailer;

class EmailProvider {

  private $mail;
  private $data;
  private $error;

  public function __construct(){
    $this->mail = new PHPMailer(true);
    $this->data = new stdClass();

    $this->mail->isSMTP();
    $this->mail->isHTML();
    $this->mail->setLanguage("br");

    $this->mail->SMTPAuth = true;
    $this->mail->SMTPSecure = "tls";
    $this->mail->CharSet = "utf-8";

    $this->mail->Host = MAIL['host'];
    $this->mail->Port = MAIL['port'];
    $this->mail->Username = MAIL['user'];
    $this->mail->Password = MAIL['password'];
  }

  public function add(string $subject, string $body, string $recipient_name, string $recipient_email): EmailProvider {
    $this->data->subject = $subject;
    $this->data->body = $body;
    $this->data->recipient_name = $recipient_name;
    $this->data->recipient_email = $recipient_email;
    return $this;
  }

  public function attach($pdf): EmailProvider {
    $this->mail->addStringAttachment($pdf, 'file.pdf');
    return $this;
  }

  public function send(string $from_name = MAIL['from_name'], string $from_email = MAIL['from_email']): bool {
    try {
      $this->mail->Subject = $this->data->subject;
      $this->mail->msgHTML($this->data->body);
      $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);
      $this->mail->setFrom($from_email, $from_name);
      $this->mail->send();
      return true;
    } catch (Exception $e) {
      $this->error = $e;
      return false;
    }
  }

  public function error(): ?Exception {
    return $this->error;
  }

}