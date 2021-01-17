<?php

namespace Source\Controller;

use Source\Providers\EmailProvider;

class Email {

  private $hasAttach = false;
  
  public function send($data){  
    if(isset($_FILES['pdf'])) {
      $this->hasAttach = true;
      $path = PATH_UPLOADS_PDF;
      $filename = $_FILES['pdf']['name'];
      $fullPathFile = $path.$filename;
      $tmpName= $_FILES['pdf']['tmp_name'];
      move_uploaded_file($tmpName, $fullPathFile);
    }
    $emailProvider = new EmailProvider();
    if($this->hasAttach){      
    $emailProvider->add(
      $data["subject"],
      $data["body"],
      $data["recipient_name"],
      $data["recipient_email"],
    // )->send();
    )->attach(
      $fullPathFile,
      $filename
    )->send();
    } else {
      $emailProvider->add(
        $data["subject"],
        $data["body"],
        $data["recipient_name"],
        $data["recipient_email"],
      )->send();
    }
  
    if(!$emailProvider->error()) {
      echo json_encode(true);
    } else {
      echo json_encode($emailProvider->error()->getMessage());
    }

  }

}