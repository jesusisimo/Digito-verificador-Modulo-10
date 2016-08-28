<?php
/**
* Clase digito verificador
*/
class Referencia{
  const min_longitud = 1; 
  const max_longitud = 36;
  public $algoritmo = array();
  public $referencia;
  public $digito_verificador; 
  public $tabla_base = array('A' => "1", 'B' => "2",'C' => "3",'D' => "4",'E' => "5",'F' => "6",'G' => "7",'H' => "8",'I' => "9",'J' => "1",'K' => "2",'L' => "3",'M' => "4",'N' => "5",'O' => "6",'P' => "7",'Q' => "8",'R' => "9",'S' => "2",'T' => "3",'U' => "4",'V' => "5",'W' => "6",'X' => "7",'Y' => "8",'Z' => "9");
  public $caracteres_validos = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
  function __construct($referencia)
  {
    $this->referencia=$referencia;
    self::normalizar();
    self::generarDigito();
  }
  private function normalizar(){
    self::limpiarCadena();
    $this->referencia = mb_convert_case($this->referencia, MB_CASE_UPPER, "UTF-8");
    $nueva_cadena="";
    for ($i=0; $i < strlen($this->referencia); $i++) {
      if (in_array($this->referencia[$i], $this->caracteres_validos)) {
        $nueva_cadena.=$this->referencia[$i];
      }
    }
    $this->referencia=substr($nueva_cadena, 0, self::max_longitud);
  }
  private function limpiarCadena(){
    $string=$this->referencia;
    $string = trim($string); 
    $string = str_replace( 
                          array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), 
                          array('A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A'), 
                          $string 
                          ); 
    $string = str_replace( 
                          array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), 
                          array('E', 'E', 'E', 'E', 'E', 'E', 'E', 'E'), 
                          $string 
                          ); 
    $string = str_replace( 
                          array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), 
                          array('I', 'I', 'I', 'I', 'I', 'I', 'I', 'I'), 
                          $string 
                          ); 
    $string = str_replace( 
                          array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), 
                          array('O', 'O', 'O', 'O', 'O', 'O', 'O', 'O'), 
                          $string 
                          ); 
    $string = str_replace( 
                          array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), 
                          array('U', 'U', 'U', 'U', 'U', 'U', 'U', 'U'), 
                          $string 
                          ); 
    $string = str_replace( 
                          array('ñ', 'Ñ', 'ç', 'Ç', 'ý', 'Ý'), 
                          array('N', 'N', 'C', 'C', 'Y', 'Y'), 
                          $string 
                          ); 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", ".", " "), '', $string ); 
    $this->referencia=$string;
  }
  private function verTabla(){
    print_r($this->tabla_base);
  }
  public function verReferencia(){
    echo $this->referencia."-".$this->digito_verificador;
  }
  private function generarDigito(){
    $referencia_del_cliente=array();
    $algoritmo_de_validacion=array();
    $multiplicacion = array();
    $resultado_multiplicacion=0;
    $j=strlen($this->referencia)-1;
    for ($i=0; $i <strlen($this->referencia) ; $i++){
      if (!is_numeric($this->referencia[$i])) {
        array_push($referencia_del_cliente, $this->tabla_base[$this->referencia[$i]]);
      }else{
        array_push($referencia_del_cliente, $this->referencia[$i]);
      }
      if ($i==0) {
        $algoritmo_de_validacion[$j]='2';
      }elseif ($i%2==0) {
        $algoritmo_de_validacion[$j]='2';
      }else{
        $algoritmo_de_validacion[$j]='1';
      }
      $j--;
    }
    
    for ($i=0; $i < count($referencia_del_cliente) ; $i++){
       array_push($multiplicacion, ($referencia_del_cliente[$i]*$algoritmo_de_validacion[$i]));
    }
    for ($i=0; $i < count($multiplicacion) ; $i++){
       if ($multiplicacion[$i]>=10) {
          $numero=$multiplicacion[$i];
          $sumatoria=0;
          for ($j=0; $j < strlen($numero); $j++) {
            $sumatoria = $sumatoria+substr($numero, $j, 1);
          }
         $multiplicacion[$i]=$sumatoria;
       }
    }
    for ($i=0; $i < count($multiplicacion) ; $i++){
      $resultado_multiplicacion=$resultado_multiplicacion+$multiplicacion[$i];
    }
    if ($resultado_multiplicacion>=10) {
      $this->digito_verificador=substr($resultado_multiplicacion, (strlen($resultado_multiplicacion)-1), 1);
    }else{
      $this->digito_verificador=$resultado_multiplicacion;
    }
    if ($this->digito_verificador>0) {
      $this->digito_verificador=10-$this->digito_verificador;
    }
  }
  function __destruct(){
    $this->referencia="";
  }
}
//$referencia = new Referencia(("16005"));
//echo $referencia->referencia."-".$referencia->digito_verificador;
//$referencia->verReferencia();
?>