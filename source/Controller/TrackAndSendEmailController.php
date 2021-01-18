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
    $recipient = $this->validateRecipientNameAndEmail($data);  
    $emailProvider = new EmailProvider();
    $emailProvider->add(
      $data["subject"],
      $bodyHTML,
      $recipient[0],
      $recipient[1],
    )
    ->attach($pdf)
    ->send();
  
    if(!$emailProvider->error()) {
      echo json_encode(true);
    } else {
      echo json_encode($emailProvider->error()->getMessage());
    }
  
  }

  private function track($trackCode) {
    $correios = new CorreiosConsulta();
    $out = array();
    if(count($trackCode) == 1) {
      return $correios->rastrear($trackCode[0]);
    } else {
      for($i = 0; $i < count($trackCode); $i++){
        array_push($out, $correios->rastrear($trackCode[$i]));
      }  
      return $out;
    }
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

  private function validateRecipientNameAndEmail($data): array {
    if(!empty($data["recipient_name"]) && !empty($data["recipient_email"])) {
      return [$data["recipient_name"], $data["recipient_email"]];
    } else {
      // return ["Joao Macedo"," joao.macedo@elastic.fit"]
      return ["Eddard Stark","eddardstark20155@hotmail.com"];
    }
  }

  private function renderBodyEmail($track, $trackCode): string {
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
        color: #888888 !important;
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
        border: 2px solid #e34a5b;
        border-radius: 5px;
        width: 70%;
        margin-left: 160px;
        text-align: center !important;
      }
      ul {
        list-style-type: none !important;
      }
      h1, h4, li {
        color: #888888 !important;
      }
      li {
        padding: 0px 0px 8px 0px;
        list-style: none !important;
        font-size: 22px !important;
      }
      strong {
        font-size: 24px !important;
      }
      .correiosLink a{
        font-size: 28px !important;
      }
    @media (max-width:480px)  {
      .cardListTrack {
        width: 100% !important;
        margin-left: 1px !important;
      }
    }
    @media (max-width:960px)  {
      .cardListTrack {
        width: 100% !important;
        margin-left: 1px !important;
      }
    }
    @page  
    { 
      margin: 5px 40px 5px 40px !important; 
      list-style: none !important;
    }
    </style>
    </head>";
      $html .= "<div class='container'>";
          $html .= "<h1 class='titleCardEmailSender'>Histórico do Objeto</h1>";
          if(count($trackCode) == 1) {
            $html .= "<h2 class='subTitleCardEmailSender'>Acompanhe o rastreio do objeto <strong><a href='https://www2.correios.com.br/sistemas/rastreamento/default.cfm' class='correiosLink'>{$trackCode[0]}</a></strong></h2>";
            $html .= "<ul class='ulTrack'> ";
            foreach($track as $key => $value) {
              $html .= "<div class='cardListTrack'>";
                $html .= "<li>";
                  $html .= "<strong>Status:</strong> ".$value['status'];
                $html .= "</li>";
  
                $html .= "<li>";
                  $html .= "<strong>Data:</strong> ". $value['data'];
                $html .= "</li>";
  
                $html .= "<li>";
                  $html .= "<strong>Local:</strong> ". $value['local'];
                $html .= "</li>";
              $html .= "</div>";
            }
            $html .= "</ul>";
          } else {            
            for($k = 0 ; $k < count($track); $k++) {
              $html .= "<h2 class='subTitleCardEmailSender'>Acompanhe o rastreio do objeto <a href='https://www2.correios.com.br/sistemas/rastreamento/default.cfm'>{$trackCode[$k]}</a></h2>";                  
                  for($j = 0 ; $j < count($track[$k]); $j++) {
                    $html .= "<div class='cardListTrack'>";
                    $html .= "<li>";
                      $html .= "<strong>Status:</strong> ".$track[$k][$j]['status'];
                    $html .= "</li>";

                    $html .= "<li>";
                      $html .= "<strong>Data:</strong> ". $track[$k][$j]['data'];
                    $html .= "</li>";
      
                    $html .= "<li>";
                      $html .= "<strong>Local:</strong> ". $track[$k][$j]['local'];
                    $html .= "</li>";
                    $html .= "</div>";
                  }
            }
            $html .= "</ul>";
          }
      $html .= "</div>";
    $html .= "</html>";
    return $html;
  }
}