<?php

namespace App\Helpers;

use \Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Utilidade
{

  public static function arrayToInteger($arr)
  {

    $newArr = [];

    foreach ($arr as  $value) {
      $newArr[] = intval($value);
    }

    return $newArr;
  }

  public static function toJson($data, $status = 200)
  {

    $header = array(
      'Content-Type' => 'application/json; charset=UTF-8',
      'charset' => 'utf-8',
      'Cache-Control' => 'no-cache, no-store, must-revalidate',
      'Pragma' => 'no-cache',
      'Expires' => '0',
      'Version' => config('app.version'),
      'Access-Control-Allow-Origin' => '*',
      'Access-Control-Allow-Methods' => '*',
      'Access-Control-Allow-Headers' => '*'
    );

    // $response = array(
    //   'status' => intval($status),
    //   'msg' => $msg,
    //   'data' => $data,
    //   'options' => $options,
    // );

    if ($status != 200) {
      $data = [
        'error' => $data
      ];
    }

    return response()
      ->json($data, $status, $header, JSON_UNESCAPED_UNICODE, true)
      ->setData($data);
  }

  public static function arrayToText($file, $array)
  {

    if (!file_exists($file)) return;

    $fp = fopen($file, 'w');

    foreach ($array as $value) {
      fwrite($fp, $value . PHP_EOL);
    }

    fclose($fp);
  }

  public static function createFile($path, $filename, $createEmptyFile = false)
  {

    $simplepath = 'app/' . $path;
    $path = storage_path($simplepath);

    if (!File::exists($path . $filename)) {
      if (!File::isDirectory($path)) {
        File::makeDirectory($path, 0777, true);
      }
    }

    if ($createEmptyFile !== false) {
      file_put_contents($path . $filename, $createEmptyFile);
    }

    return [
      'exists' => File::exists($path . $filename),
      'path' => $path,
      'fullpath' => $path . $filename,
      'simplepath' => $simplepath . $filename,
      'filename' => $filename,
      'filecontent' => $createEmptyFile,
    ];
  }

  public static function toDate($value = null, $format_in = 'd/m/Y', $format_out = 'Y-m-d H:i:s')
  {

    try {

      $format_in = ($format_in ? $format_in : 'd/m/Y');
      $format_out = ($format_out ? $format_out : 'Y-m-d H:i:s');

      if (!$value) {
        $value = Carbon::now();
        $format_in = 'Y-m-d H:m:s';
        return date($format_out);
      }
      if (!empty($value)) {
        $date = Carbon::createFromFormat($format_in, $value);
        return $date->format($format_out);
      }

      return null;
    } catch (\Exception $e) {
      return null;
    }
  }

  public static function dateAdd($add, $date = null, $type = 'M', $uteis = false)
  {

    if ($date == null) {
      $date = Carbon::now();
    } else {
      $date = Carbon::parse($date);
    }

    $retorno = $date;

    switch ($type) {

      case 'M':
        $retorno = $date->addMonths($add);
        break;

      case 'D':
        $retorno = $date->addDays($add);
        $add = self::totalFeriado(Carbon::now(), $retorno);

        if ($add > 0 && $uteis) {
          $retorno = $retorno->addDays($add);
        }
        break;

      case 'Y':
        $retorno = $date->addYears($add);
        break;

      case 'm':
        $retorno = $date->addMinutes($add);
        break;

      case 's':
        $retorno = $date->addSeconds($add);
        break;

      case 'h':
        $retorno = $date->addHours($add);
        break;
    }

    if ($uteis) {

      $dia = $retorno->day;
      $mes = $retorno->month;
      $ano = $retorno->year;

      $arrDiasUteis = self::diasUteis($mes, $ano);


      if (!in_array($dia, $arrDiasUteis)) {

        foreach ($arrDiasUteis as $item) {
          if ($item > $dia) {

            $timestamp = mktime(0, 0, 0, $mes, $item, $ano);
            $data = date("Y-m-d H:i:s", $timestamp);

            return $data;
          }
        }

        $ano = ($mes == 12 ? ($ano + 1) : $ano);
        $mes = ($mes == 12 ? 1 : ($mes + 1));

        $arrDiasUteis = self::diasUteis($mes, $ano);

        $dia = $arrDiasUteis[0];

        $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
        $data = date("Y-m-d H:i:s", $timestamp);

        return $data;
      }
    }
    return $retorno->toDateTimeString();
  }


  public static function formataCampo($valor, $left, $format = '0')
  {
    return str_pad($valor, $left, $format, STR_PAD_LEFT);
  }

  public static function isEmail($email)
  {

    if (filter_var($email, FILTER_VALIDATE_EMAIL))
      return true;

    return false;
  }

  public static function convertDate($value, $format = 'Y-m-d')
  {
    return self::toDate($value, 'd/m/Y', 'Y-m-d');

    $value = strtotime($value);
    return date($format, $value);
  }

  public static function dateDiff($dt_maior = null, $dt_menor = null, $str_interval = 'd', $relative = true)
  {

    $dt_menor = (!$dt_menor ? date("Y-m-d H:i:s") : $dt_menor);
    $dt_maior = (!$dt_maior ? date("Y-m-d H:i:s") : $dt_maior);

    if (is_string($dt_menor)) $dt_menor = date_create($dt_menor);
    if (is_string($dt_maior)) $dt_maior = date_create($dt_maior);

    $diff = date_diff($dt_menor, $dt_maior, !$relative);

    switch ($str_interval) {
      case "y":
        $total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
        break;
      case "m":
        $total = (($diff->y * 12) + $diff->m + ($diff->d / 30) + ($diff->h / 24));
        break;
      case "d":
        $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
        break;
      case "h":
        $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;
        break;
      case "i":
        $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s / 60;
        break;
      case "s":
        $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;
        break;
    }

    if ($diff->invert && $total >= 1) {
      return -1 * $total;
    }

    return $total;
  }

  public static function hideEmail($email)
  {

    $mail_parts = explode("@", $email);
    $length = strlen($mail_parts[0]);
    $show = floor($length / 2);
    $hide = $length - $show;
    $replace = str_repeat("*", $hide);

    return substr_replace($mail_parts[0], $replace, $show, $hide) . "@" . substr_replace($mail_parts[1], "**", 0, 2);
  }

  public static function textLimit($text, $max)
  {

    if (strlen($text) > $max) {
      return substr($text, 0, $max);
    }

    return $text;
  }

  public static function generatePassword($length, $onlyNumber = false, $lowercase = false)
  {

    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';

    if ($onlyNumber) {
      $password =  substr(str_shuffle(self::onlyNumber($data)), 0, $length);
    } else {
      $password = substr(str_shuffle($data), 0, $length);
    }

    if ($lowercase) {
      $password = self::lowerCase($password);
    }

    return $password;
  }

  public static function validarData($data)
  {

    try {
      $data = self::convertDate($data);
    } catch (\Exception $e) {
      throw new \Exception('Data inválida. Informe uma data no formato dd/mm/yyyy');
    }

    $arrData = explode("-", $data);

    if (count($arrData) != 3)
      throw new \Exception('Data inválida. Informe uma data no formato dd/mm/yyyy');

    $d = $arrData[2];
    $m = $arrData[1];
    $y = $arrData[0];

    $res = checkdate($m, $d, $y);

    if ($res == 1) {
      return true;
    }

    throw new \Exception('Data inválida. Informe uma data no formato dd/mm/yyyye');
  }

  public static function validarCpf($cpf)
  {
    $cpf = self::onlyNumber($cpf);

    if (strlen($cpf) != 11) throw new \Exception('CPF inválido');

    $sum = 0;
    $cpf = str_split($cpf);
    $cpftrueverifier = array();
    $cpfnumbers = array_splice($cpf, 0, 9);
    $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);

    for ($i = 0; $i <= 8; $i++) {
      $sum += $cpfnumbers[$i] * $cpfdefault[$i];
    }

    $sumresult = $sum % 11;

    if ($sumresult < 2) {
      $cpftrueverifier[0] = 0;
    } else {
      $cpftrueverifier[0] = 11 - $sumresult;
    }

    $sum = 0;
    $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
    $cpfnumbers[9] = $cpftrueverifier[0];

    for ($i = 0; $i <= 9; $i++) {
      $sum += $cpfnumbers[$i] * $cpfdefault[$i];
    }

    $sumresult = $sum % 11;

    if ($sumresult < 2) {
      $cpftrueverifier[1] = 0;
    } else {
      $cpftrueverifier[1] = 11 - $sumresult;
    }

    $returner = false;

    if ($cpf == $cpftrueverifier) {
      $returner = true;
    }

    $cpfver = array_merge($cpfnumbers, $cpf);

    if (count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0)) {
      $returner = false;
    }

    if ($returner)
      return true;

    throw new \Exception("CPF inválido");
  }

  public static function validarCnpj($cnpj)
  {

    $cnpj = self::onlyNumber($cnpj);

    // Valida tamanho
    if (strlen($cnpj) != 14)
      throw new \Exception('CNPJ inválido');

    // Valida primeiro dígito verificador
    for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
      $soma += $cnpj[
        $i] * $j;
      $j = ($j == 2) ? 9 : $j - 1;
    }

    $resto = $soma % 11;

    if ($cnpj[
      12] != ($resto < 2 ? 0 : 11 - $resto))
      throw new \Exception('CNPJ inválido');

    // Valida segundo dígito verificador
    for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
      $soma += $cnpj[
        $i] * $j;
      $j = ($j == 2) ? 9 : $j - 1;
    }

    $resto = $soma % 11;

    $returner = $cnpj[
      13] == ($resto < 2 ? 0 : 11 - $resto);

    if ($returner)
      return true;

    throw new \Exception("CNPJ inválido");
  }

  public static function upperCase($value)
  {
    return strtr(strtoupper($value), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
  }

  public static function lowerCase($value)
  {
    return strtr(strtolower($value), "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß", "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
  }

  public static function onlyNumber($value)
  {

    if (!$value) return '';

    $value = preg_replace("/[^0-9]/", "", $value);

    if (!$value)
      return null;

    return $value;
  }

  public static function onlyMoney($value)
  {

    if (!$value) return '';

    $value = preg_replace("/[^0-9,.]/", "", $value);
    return $value;
  }

  public static function mask($val, $mask)
  {

    if (!$val) return $val;

    $maskared = '';
    $k = 0;

    for ($i = 0; $i <= strlen($mask) - 1; $i++) {

      if ($mask[$i] == '#') {
        if (isset($val[$k]))
          $maskared .= $val[$k++];
      } else {
        if (isset($mask[$i]))
          $maskared .= $mask[$i];
      }
    }

    return $maskared;
  }

  public static function removeSpecialChar($texto)
  {

    $texto = preg_replace(
      array(
        "/(á|à|ã|â|ä)/",
        "/(Á|À|Ã|Â|Ä)/",
        "/(é|è|ê|ë)/",
        "/(É|È|Ê|Ë)/",
        "/(í|ì|î|ï)/",
        "/(Í|Ì|Î|Ï)/",
        "/(ó|ò|õ|ô|ö)/",
        "/(Ó|Ò|Õ|Ô|Ö)/",
        "/(ú|ù|û|ü)/",
        "/(Ú|Ù|Û|Ü)/",
        "/(ñ)/",
        "/(Ñ)/",
      ),
      explode(" ", "a A e E i I o O u U n N"),
      $texto
    );

    $texto = preg_replace("/ç/", "c", $texto);
    $texto = preg_replace("/Ç/", "C", $texto);
    //$texto = preg_replace('/[^A-Za-z0-9 ]/', '', $texto);

    return trim($texto);
  }

  public static function moneyToInteger($value)
  {

    $value = self::onlyMoney($value);

    return floatval(trim(str_replace(",", ".", str_replace(".", "", $value)))) * 100;
  }

  public static function moneyToFloat($value)
  {

    return floatval(trim(str_replace(",", ".", str_replace(".", "", $value))));
  }

  public static function floatToMoney($value)
  {

    try {
      return number_format($value, 2, ',', '.');
    } catch (\Exception $e) {
      throw new \Exception("[floatToMoney] Value `" . $value . "` " . $e->getMessage());
    }
  }

  public static function integerToMoney($value)
  {
    try {
      return number_format(floatval(round($value / 100, 2)), 2, ',', '.');
    } catch (\Exception $e) {
      throw new \Exception("[integerToMoney] Value `" . $value . "` " . $e->getMessage());
    }
  }

  public static function integerToFloat($value)
  {

    try {
      return floatval(round($value / 100, 2));
    } catch (\Exception $e) {
      throw new \Exception("[integerToFloat] Value `" . $value . "` " . $e->getMessage());
    }
  }

  public static function integerToPercentAcressimo($value)
  {
    return (($value / 100)) + 1;
  }

  public static function integerToPercentDesconto($value)
  {
    return 1 - (($value / 100));
  }

  public static function calcularJurosParcela($valor, $parcela, $taxa)
  {

    $taxa = $taxa / 100;

    $valParcela = $valor * pow((1 + $taxa), $parcela);

    return $valParcela / $parcela;
  }

  public static function diasUteis($mes, $ano)
  {

    $uteis = 0;
    $arrUteis = [];
    $arrFeriado = self::getFeriados($ano);
    // Obtém o número de dias no mês
    // (http://php.net/manual/en/function.cal-days-in-month.php)
    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    for ($dia = 1; $dia <= $dias_no_mes; $dia++) {

      // Aqui você pode verifica se tem feriado
      // ----------------------------------------
      // Obtém o timestamp
      // (http://php.net/manual/pt_BR/function.mktime.php)
      $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
      $semana    = date("N", $timestamp);

      if ($semana < 6 && isset($arrFeriado[$mes]) && !in_array($dia, $arrFeriado[$mes])) {
        $uteis++;
        array_push($arrUteis, $dia);
      }
    }

    return $arrUteis;
  }

  public static function getFeriados($ano)
  {
    $dia = 86400;
    $datas = array();
    $datas['pascoa'] = easter_date($ano);
    $datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
    $datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
    $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);

    $carnaval_mes = intval(date('m', $datas['carnaval']));
    $carnaval_dia = intval(date('d', $datas['carnaval']));
    $sexta_santa_mes = intval(date('m', $datas['sexta_santa']));
    $sexta_santa_dia = intval(date('d', $datas['sexta_santa']));
    $pascoa_mes = intval(date('m', $datas['pascoa']));
    $pascoa_dia = intval(date('d', $datas['pascoa']));
    $corpus_cristi_mes = intval(date('m', $datas['corpus_cristi']));
    $corpus_cristi_dia = intval(date('d', $datas['corpus_cristi']));

    $feriados = [
      '1' => [1],
      '2' => [],
      '3' => [],
      '4' => [21],
      '5' => [1],
      '6' => [],
      '7' => [],
      '8' => [],
      '9' => [7],
      '10' => [12],
      '11' => [2, 15],
      '12' => [25],
    ];

    array_push($feriados[$carnaval_mes], $carnaval_dia);
    array_push($feriados[$sexta_santa_mes], $sexta_santa_dia);
    array_push($feriados[$pascoa_mes], $pascoa_dia);
    array_push($feriados[$corpus_cristi_mes], $corpus_cristi_dia);

    return $feriados;
  }

  public static function totalFeriado($data1, $data2)
  {

    $begin = new \DateTime($data1);
    $end = new \DateTime($data2);
    $end = $end->modify('+1 day');

    $interval = new \DateInterval('P1D');
    $daterange = new \DatePeriod($begin, $interval, $end);

    $dia_count = 0;
    foreach ($daterange as $value) {

      $dia = intval($value->format("d"));
      $mes = intval($value->format("m"));
      $ano = intval($value->format("Y"));

      $arrFeriado = self::getFeriados($ano);

      $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
      $semana    = date("N", $timestamp);

      if ($semana == 6 || $semana == 7 || (isset($arrFeriado[$mes]) && in_array($dia, $arrFeriado[$mes]))) {
        $dia_count++;
      }
    }

    return $dia_count;
  }

  public static function getHeader($header)
  {
    return request()->header($header);
  }

  public static function calcularDistanciaCoordenadas($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
  {
    $rad = M_PI / 180;
    //Calculate distance from latitude and longitude
    $theta = $longitudeFrom - $longitudeTo;
    $dist = sin($latitudeFrom * $rad)
      * sin($latitudeTo * $rad) +  cos($latitudeFrom * $rad)
      * cos($latitudeTo * $rad) * cos($theta * $rad);

    $distancia = acos($dist) / $rad * 60 *  1.853;

    return self::floatToMoney($distancia);
  }

  public static function coordenadasPorEndereco($endereco)
  {
    $client = new \GuzzleHttp\Client();

    $geocoder = new \Spatie\Geocoder\Geocoder($client);
    $geocoder->setApiKey(config('geocoder.key'));
    $geocoder->setCountry(config('geocoder.country'));

    return $geocoder->getCoordinatesForAddress($endereco);
  }

  public static function removeAcentos($texto)
  {

    $texto = preg_replace(
      array(
        "/(á|à|ã|â|ä)/",
        "/(Á|À|Ã|Â|Ä)/",
        "/(é|è|ê|ë)/",
        "/(É|È|Ê|Ë)/",
        "/(í|ì|î|ï)/",
        "/(Í|Ì|Î|Ï)/",
        "/(ó|ò|õ|ô|ö)/",
        "/(Ó|Ò|Õ|Ô|Ö)/",
        "/(ú|ù|û|ü)/",
        "/(Ú|Ù|Û|Ü)/",
        "/(ñ)/",
        "/(Ñ)/",
      ),
      explode(" ", "a A e E i I o O u U n N"),
      $texto
    );

    $texto = preg_replace("/ç/", "c", $texto);
    $texto = preg_replace("/Ç/", "C", $texto);
    //$texto = preg_replace('/[^A-Za-z0-9 ]/', '', $texto);

    return trim($texto);
  }
}
