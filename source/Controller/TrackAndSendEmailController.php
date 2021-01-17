<?php

namespace Source\Controller;

use Cagartner\CorreiosConsulta\CorreiosConsulta;
use Source\Providers\EmailProvider;

class TrackAndSendEmailController{

  private $hasAttach = false;
  
  public function send($data){  
    $track = $this->track($data["trackCode"]);
    $bodyHTML = $this->renderBodyEmail($track, $data["trackCode"]);
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
      $bodyHTML,
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

  private function track($trackCode){
    $correios = new CorreiosConsulta();
    $out = $correios->rastrear($trackCode);
    return $out;
  }

  private function renderBodyEmail($track, $trackCode){
    $html = "
    <html>
    <head>
      <style>  
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      } 
      .container{
      }        
      .wrapper{
        justify-content: center;
        align-items: center;
      }        
      .titleCardEmailSender{
        margin-bottom: 30px;
        text-align: center;
      }
      .subTitleCardEmailSender {
        margin-bottom: 15px;
        text-align: center;
        color: #e34a5b !important;
      }
      .subTitleCardEmailSender a{
        margin-bottom: 15px;
        text-align: center;
        color: #e34a5b !important;
        font-size: 20px;
      }
      .cardListTrack {
        margin: 15px auto 15px auto;
        border: 1.5px solid #e34a5b;
        border-radius: 5px;
        width: 50%;
      }
      ul {
        list-style-type: none;
      }
      h1, h4, li {
        color: #383838 !important;
      }
      li {
        padding: 5 0px 5 0px;
      }
      .cardListTrack{
        text-align: center !important;
      }
    @media (max-width:480px)  {
      .cardListTrack {
        width: 100% !important;
      }
    }
    @media (max-width:960px)  {
      .cardListTrack {
        width: 100% !important;
      }
    }
    </style>
    </head>";
      $html .= "<div class='container'>";
        // $html .= "<div class='wrapper'>";
          $html .= "<h1 class='titleCardEmailSender'>Histórico do Objeto</h1>";
          $html .= "<h4 class='subTitleCardEmailSender'>Acompanhe o rastreio do objeto <a href='https://www2.correios.com.br/sistemas/rastreamento/default.cfm'>{$trackCode}</a></h4>";
          $html .= "<ul id='ulTrack'> ";
          foreach($track as $key => $value) {
            $html .= "<div class='cardListTrack'>";
              $html .= "<li>";
                $html .= "Status: ".$value['status'];
              $html .= "</li>";

              $html .= "<li>";
                $html .= "Data: ". $value['data'];
              $html .= "</li>";

              $html .= "<li>";
                $html .= "Local: ". $value['local'];
              $html .= "</li>";
            $html .= "</div>";
          }
          $html .= "</ul>";
        // $html .= "</div>";
      $html .= "</div>";
    $html .= "</html>";
    return $html;
  }
}