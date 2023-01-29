<?php
// ini_set('memory_limit', '512M');
// ini_set ("max_execution_time", "1200");
ini_set('display_errors', 1);
// date_default_timezone_set('America/Argentina/Buenos_Aires');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if(!defined('RAIZ')) define('RAIZ', realpath(dirname(__FILE__, 2)) . DS);
if(!defined('NUCLEO_PATH')) define('NUCLEO_PATH', RAIZ . 'nucleo' . DS);
if(!defined('BASE')) define('BASE', 'https://thequickdivorce.com/');

require_once NUCLEO_PATH . 'Autoload.php';
// require_once NUCLEO_PATH . 'Pd.php';
// require_once RAIZ .  'libs/Pd.php';

// echo RAIZ;
// echo date('Y-m-d H:i:s');exit;

$_fecha_hoy = date('Y-m-d');


$m = new TrabajosBase();

$_data = $m->traerForms();

echo"<pre>";print_r($_data);echo"</pre>";exit;

if($_data){

    for ($i=0; $i < count($_data); $i++) { 
        
        $_aviso = $m->traerFormsAviso($_data[$i]['id']);

        if($_aviso){

            $_dif = $m->diferenciaDias($_aviso['fecha'], $_fecha_hoy);

            echo $_dif."<br>";

            if($_dif >= 7){

                // envio de mail
                $_user = $m->traerUser($_data[$i]['id_user']);
                $_envio = $m->envioAviso($_user['email'], $_user['nombre'].' '.$_user['apellido']);
                if($_envio){
                    echo "se envio<br>";

                    // agregar fecha a BD
                    $_save = $m->guardarData($_data[$i]['id'],$_fecha_hoy);
                    if($_save){
                        echo "se guardo el aviso<br>";
                    }
                }
            }
        }


    }

}





/////////////////////////////////////////////////////////

class trabajosBase
{
    private $datos;
    
    public function __construct()
    {
        // $this->datos = array(); 
    }   

    private static function con()
    {
        try
        {
            $con = new PDO("mysql:dbname=quickdivorce2022;host=localhost","qd_webmaster","gzqqJMhB12NT");
            $con->exec("set names utf8");
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;
        }
        catch( PDOException $e)
        {       
            echo "Error Connection: " . $e->getMessage();
        }
        
    }

    public function traerForms()
    {
        $_data = self::con()->prepare("SELECT cfr.* FROM `contenidos_forms_respuestas` as cfr
                                                        WHERE (cfr.estado = 'incomplete' OR cfr.estado = 'not started') AND
                                                        NOW() < cfr.fecha_aviso_1
                                                        ORDER BY cfr.id ASC");
        $_data->execute();
        $_data = $_data->fetchAll(PDO::FETCH_ASSOC);    

        return ($_data) ? $_data : false;
    }

    public function traerFormsAviso($_id)
    {
        $_id = (int) $_id;
        $_data = self::con()->prepare("SELECT cfa.* FROM `contenidos_forms_avisos` as cfa WHERE cfa.id_form = :id ORDER BY cfa.id DESC");
        $_data->execute(array(':id' => $_id));
        $_data = $_data->fetch(PDO::FETCH_ASSOC);    

        return ($_data) ? $_data : false;
    }

    public function diferenciaDias($fecha_inicial, $fecha_final)
    {
        $dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
        $dias = abs($dias); $dias = floor($dias);

        return $dias;
    }

    public function traerUser($_id)
    {
        $_id = (int) $_id;
        $result = self::con()->prepare("SELECT cu.* FROM `contenidos_users` as cu WHERE cu.id = :id");
        $result->execute(array(":id" => $_id));
        $result = $result->fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result : false;
    }

    public function guardarData($_id, $_fecha)
    {
        $_id = (int) $_id;
        $_data = self::con()->prepare("INSERT INTO `contenidos_forms_avisos` (id_form,fecha) VALUES (:id,:fecha)");
        $_data->execute(array(':id' => $_id, ':fecha' => $_fecha));

        return ($_data) ? $_data : false;
    } 


    public function envioAviso($_email, $_nombre)
    {
        require_once RAIZ.'vendor/autoload.php';

        $_body = '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <link rel="preconnect" href="https://fonts.gstatic.com">
                        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
                        <link href="https://fonts.googleapis.com/css2?family=Karla:wght@300&display=swap" rel="stylesheet">
                        <title>The Quick Divorce</title>
                    </head>

                    <body style="margin: auto;width: 50%;padding:0;box-sizing: border-box;min-width: 600px;font-family:Arial, Helvetica, sans-serif;;color: #000;">

                       <header class="header" style="height: 100px;background-color: #162536;">
                           <img src="'.BASE.'views/layout/default/img/TQD_logo.png" alt="" style="width: 340px;margin: auto;display: block;padding-top: 10px;">
                       </header> 

                       <div class="container" style="background-color: #f2f0e2; padding: 0 50px;">

                            <div class="intro-text" >
                                <br><br>
                                <h3 class="text-big" style="margin: 0;padding-top:10px">Hi '.self::convertirCaracteres($_nombre).'</h3>
                                <p class="text-small" >Thank you for choosing us to support you in this important transition. Remember to complete your online interview here so we can take care of the rest.  </p>

                                <p class="text-small" >We know that these decisions take time, so you have <strong>six months to finalize all your paperwork. If you have not completed your interview in six months, you will look access to your documents.</strong></p>


                                <p class="text-small" >Once you “submit” your interview we will email you the PDFs that require signatures,  keep you posted on the electronic filing, and make sure you receive an email containing the documents filed with the Court.</p>
                                
                                <p class="text-small" >Click below to login to your account or visit our  FAQ section <a href="'.BASE.'faqs">here</a></p>


                                <a href="'.BASE.'user" style="display: block;width: 190px;padding: 5px;border-radius: 4px;margin: 25px auto 0;box-shadow: 3px 5px 10px rgba(0,0,0,0.2);outline: 0;border: 0;background: #c5d7de;cursor: pointer;"><p style="height: 100%; width: 100%; display: block; border: solid 1px #fff; color: #1d2731; font-family:\'gothambold\', sans-serif; font-weight: bold; font-size: 13px; text-align: center; text-transform: uppercase; padding: 10px 0; margin: 0;">GET STARTED</p></a>

                                <p class="text-small">
                                    Regards,<br>
                                    Aliette Carolan & The Quick Divorce Team<br>
                                    <a href="'.BASE.'">https://thequickdivorce.com</a>
                                </p>
                            </div>

                            <br><br>
                       </div>
                    </body>
                    </html>';

        $_envioMail = new PHPMailer(true);
        $_envioMail->From ='info@thequickdivorce.com';
        $_envioMail->FromName ='The Quick Divorce';
        $_envioMail->Subject = "One more thing and then you're done!";               
        $_envioMail->Body = $_body;
        $_envioMail->AddAddress($_email);
        // $_envioMail->AddAddress('lucianodirisio@gmail.com'); 
        // $_envioMail->AddAddress('luciano@indigo.com.ar');            
        $_envioMail->IsHTML(true); 
        
        $exito = $_envioMail->Send();
        
        $intentos=1;
        
        while ((!$exito) && ($intentos < 3)) {
            // sleep(5);           
            $exito = $_envioMail->Send();              
            $intentos=$intentos+1;          
        }
        
        if(!$exito) {           
            return $_envioMail->ErrorInfo;
        }else{
            return true;
        }
    }

    public static function convertirCaracteres($_cadena)
    {
        return htmlspecialchars_decode(htmlspecialchars(html_entity_decode($_cadena)));
    }

    public static function convertirCaracteres2($_cadena)
    {
        return htmlspecialchars(html_entity_decode($_cadena));
    }

}



?>