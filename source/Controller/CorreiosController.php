<?php

namespace Source\Controller;

use Cagartner\CorreiosConsulta\CorreiosConsulta;

class CorreiosController {
  
  public function track($data){
    $correios = new CorreiosConsulta();
    $out = array_values($correios->rastrear($data['code']));
    echo json_encode($out);
  }

}