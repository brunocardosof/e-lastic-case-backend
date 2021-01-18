<?php

namespace Source\Controller;

use Cagartner\CorreiosConsulta\CorreiosConsulta;
use Source\Providers\EmailProvider;
use Dompdf\Dompdf;

class TrackAndSendEmailController{

  public function send($data){  
    $trackCode = preg_split('@;@', $data["trackCode"], NULL, PREG_SPLIT_NO_EMPTY);
    $trackCodeValidated = $this->validateTrackCode(($trackCode));
    $track = $this->track($trackCode);
    $bodyHTML = $this->renderBodyEmail($track, $trackCodeValidated);
    $pdf = $this->generatePDF($bodyHTML);
    $emailProvider = new EmailProvider();
    $emailProvider->add(
      $data["subject"],
      $bodyHTML,
      $data["recipient_name"],
      $data["recipient_email"],
    )
    ->attach($pdf)
    ->send();
  
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

  private function generatePDF($html){
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper("A4");
    $dompdf->render();
    return $dompdf->output();
  }
  private function validateTrackCode($trackCode) {
    if(empty($trackCode)) {
      return ["OA016913717BR"];
    }else {
      return $trackCode;
    }
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
        margin-bottom: 15px;
        border: 1.5px solid #e34a5b;
        border-radius: 5px;
        width: 80%;
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
      .ulTrack{
        margin-left: 210px;
      }
    @media (max-width:480px)  {
      .cardListTrack {
        width: 100% !important;
      }
      .ulTrack{
        margin-left: 1px !important;
      }
    }
    @media (max-width:960px)  {
      .cardListTrack {
        width: 100% !important;
      }
      .ulTrack{
        margin-left: 1px !important;
      }
    }
    </style>
    </head>";
      $html .= "<div class='container'>";
        // $html .= "<div class='wrapper'>";
          $html .= "<h1 class='titleCardEmailSender'>Histórico do Objeto</h1>";
          $html .= "<h4 class='subTitleCardEmailSender'>Acompanhe o rastreio do objeto <a href='https://www2.correios.com.br/sistemas/rastreamento/default.cfm'>{$trackCode}</a></h4>";
          $html .= "<ul class='ulTrack'> ";
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