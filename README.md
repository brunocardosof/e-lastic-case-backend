<h1 align="center">
  E-lastic-Case-PHP<br>
  Case proposto pela empresa E-lastic para vaga de desenvolvedor backend PHP
</h1>
<br>
<br>
<br>
<br>
<table>
  <tr>
    <td>
      <b>Rastrear multiples objetos</b><br>
      <b>POST</b> /trackAndEmail<br>
      <b>Content-Type</b>: multipart/form-data<br>
      <b>subject</b>: "Status de Rastreio de Mercadoria"<br>
      <b>recipient_name</b>: "Eddard Stark"<br>
      <b>recipient_email</b>: "eddardstark20155@hotmail.com"<br>
      <b>trackCode</b>: "OA016913717BR;QD139714677BR"<br>
    </td>
    <td>
      <b>Rastrear um unico objeto</b><br>
      <b>POST</b> /trackAndEmail<br>
      <b>Content-Type</b>: multipart/form-data<br>
      <b>subject</b>: "Status de Rastreio de Mercadoria"<br>
      <b>recipient_name</b>: "Bruno Cardoso"<br>
      <b>recipient_email</b>: "bruno.cardosof@gmail.com"<br>
      <b>trackCode</b>: "OA016913717BR"<br>
    </td>
  </tr>
  <tr>
    <td><img src="/multiples-objects.gif"></td>
    <td><img src="/single-object.gif"></td>
  </tr>
 </table>
 
<h4>O case foi desenvolvido usando o XAMPP V.3.2.4</h4>

<h1 align="left">
  Tecnologias usadas
</h1>

<h3> PHP 7.4 </h3>

<h1 align="left">
  Bibliotecas usadas
</h1>

"phpmailer/phpmailer": "6.0.7",

"coffeecode/router": "1.0.7",

"cagartner/correios-consulta": "0.3.0",

"vlucas/phpdotenv": "5.1.0",

"dompdf/dompdf": "1.0.1"

<h1 align="left"> Como Utilizar a aplicação: </h1>

<h3>Configuração das variaveis de ambiente</h3>
<b>Criar um arquivo .env na pasta raiz do projeto e adicionar as configurações do seu email, EXEMPLO:</b>

HOST="SMTP"

PORT=587

USER=bruno.cardosof@gmail.com

PASSWORD=*******
*********************************************************************
<h3>Utilizando a rota de rastreamento de objetos e envio de email</h3>

<b>POST</b> /trackAndEmail

<b>Content-Type</b>: multipart/form-data

<b>subject</b>: "Titulo do email"

<b>recipient_name</b>: "Nome do destinatário"

<b>recipient_email</b>: "Email do destinatário"

<b>trackCode</b>: "Código do rastreamento"

<h1 align="left"> Observações: </h1>
Se deixar os campos <b>recipient_name</b>, <b>recipient_email</b> e <b>trackCode</b> vazio, será enviado um email para joao.macedo@elastic.fit com nome João Macedo e o código OA016913717BR.


Para rastrear multiplos código, é necessário separa-los por ponto e virgula <b>EXEMPLO: trackCode: "OA016913717BR;QD139714677BR"</b> igual ao rastreio do site correios.

<h1 align="left">
  Insomnia File para teste:
</h1>

https://github.com/brunocardosof/e-lastic-case-backend/blob/master/Insomnia-File.json

<h1 align="left">
  Frontend para teste
</h1>

Além do Insomnia, na pasta /frontend pode-se realizar o teste de envio de email utilizando algum server http.

Se for modificado o nome da pasta raiz do projeto, é necessário configurar a variavel base_url em frontend/index.js
