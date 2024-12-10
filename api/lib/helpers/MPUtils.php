<?php

/**
 * Metodi di utilità
 *
 * @author Matteo Ferrone
 * @since 2024-06-28
 * @version 2.8.1
 */
class MPUtils {

  public function sanitizeName($name): array|string {
    $cerca = array("à", "è", "é", "ì", "ò", "ù", "'", "?", " ", "__", "&", "%", "#", "(", ")", "/", "+", "°");
    $sostituisci = array("a", "e", "e", "i", "o", "u", "-", "-", "-", "-", "e", "-per-cento-", "-", "", "", "-", "_", "_");
    $doppioMeno = array("---");
    $sostDoppioMeno = array("-");
    $newString = str_replace($cerca, $sostituisci, trim(strtolower($name)));
    return str_replace($doppioMeno, $sostDoppioMeno, trim(strtolower($newString)));
  }

  public function unSanitizeName($name): array|string {
    $cerca = array("-", "_");
    $sostituisci = array(' ', ' ');
    return str_replace($cerca, $sostituisci, trim($name));
  }

  public function levaParolacce($testo): array|string {
    $cerca = array("stronz", "merd", "cacca", "porc", "vaffanculo", "cul", "cazz", "figa", "fottere", "scopare",
        "idiot", "scem", "cretin", "deficent", "deficient", "imbecill", "pisci", "pisciare", "smerdare", "fottiti",
        "fottut", "trombare", "porko", "cornut", "troi", "puttan", "zoccol", "fregn", "suca", "minchi");
    return str_replace($cerca, "***", $testo);
  }

  public function troncaTesto($testo, $caratteri = 300) {
    if (strlen($testo) <= $caratteri) {
      return $testo;
    }
    $nuovoTesto = substr($testo, 0, $caratteri);
    $condizione1 = preg_match("/^([^<]|<[^a]|<a.*>.*<\/a>)*$/", $nuovoTesto);
    if ($condizione1 == 0) {
      $caratteri = strrpos($nuovoTesto, "<a");
      $nuovoTesto = substr($testo, 0, $caratteri); // Taglia prima del link
    }
    return $nuovoTesto;
  }

  public function scriviTesto($file, $message) {
    $f = fopen($file, 'a+');
    fwrite($f, $message);
    fclose($f);
  }

  public function chkEmail($email) {
    $email = trim($email);
    if (!$email) {
      return false;
    }
    $numAt = count(explode('@', $email)) - 1;
    if ($numAt != 1) {
      return false;
    }
    if (strpos($email, ';') || strpos($email, ',') || strpos($email, ' ')) {
      return false;
    }
    if (!preg_match('/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email)) {
      return false;
    }
    return true;
  }

  public function truncateNumberFormat($number, $decimals = 2, $decimalPoint = ',', $thousandPoint = '.') {
    if (($number * pow(10, $decimals + 1) % 10) == 5) {
      $number -= pow(10, -($decimals + 1));
    }
    return number_format($number, $decimals, $decimalPoint, $thousandPoint);
  }

  public function listaAnni($annoStart, $annoEnd) {
    $arrAnni = array();
    for ($i = $annoStart; $i <= $annoEnd; $i++) {
      $arrAnni[] = $i;
    }
    rsort($arrAnni);
    return $arrAnni;
  }

  public function parseXml($file) {
    $fileContents = file_get_contents($file);
    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    $fileContents = trim(str_replace('"', "'", $fileContents));
    $simpleXml = simplexml_load_string($fileContents);
    $json = json_encode($simpleXml);
    return $json;
  }

  public function sortObject($objA, $objB) {
    return strcmp($objA->__toString(), $objB->__toString());
  }

  public function ln2br($text) {
    return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
  }

  public function getProportionValue($a, $b, $c) {
    return (float)((float)($b * $c) / $a);
  }

  public function htmlToText($string) {
    $search = array(
        "'<script[^>]*?>.*?</script>'si",
        "'<[\/\!]*?[^<>]*?>'si",
        "'([\r\n])[\s]+'",
        "'&(quot|#34);'i",
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
        "'&(reg|#174);'i",
        "'&#8482;'i",
        "'&#149;'i",
        "'&#151;'i",
        "'&#(\d+);'e"
    );
    $replace = array(
        " ",
        " ",
        "\\1",
        "\"",
        "&",
        "<",
        ">",
        " ",
        "&iexcl;",
        "&cent;",
        "&pound;",
        "&copy;",
        "&reg;",
        "<sup><small>TM</small></sup>",
        "&bull;",
        "-",
        "uchr(\\1)"
    );
    $text = preg_replace($search, $replace, $string);
    return $text;
  }

  public function isUTF8($text) {
    $res = mb_detect_encoding($text);
    return $res == "UTF-8" || $res == "ASCII";
  }

  public function closeUnclosedTags($unclosedString): string {
    preg_match_all("/<([^\/]\w*)>/", $closedString = $unclosedString, $tags);
    for ($i = count($tags[1]) - 1; $i >= 0; $i--) {
      $tag = $tags[1][$i];
      if (substr_count($closedString, "</$tag>") < substr_count($closedString, "<$tag>")) {
        $closedString .= "</$tag>";
      }
    }
    return $closedString;
  }

  public function vatGetValue($amount, $vatPercent = 22): float|int {
    return ($amount * $vatPercent) / 100;
  }

  public function windowClose($reloadOpener = false): void {
    if (!$reloadOpener) {
      echo "<script  type=\"text/javascript\" >\nwindow.self.close()\n</script>\n";
    } else {
      echo "<script type=\"text/javascript\" >\nwindow.opener.location.reload(true);window.self.close()\n</script>\n";
    }
  }

  public function arrayToXml($array, &$xml): void {
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        if (!is_numeric($key)) {
          $subnode = $xml->addChild("$key");
          $this->arrayToXml($value, $subnode);
        } else {
          $subnode = $xml->addChild("item$key");
          $this->arrayToXml($value, $subnode);
        }
      } else {
        $xml->addChild("$key", htmlspecialchars("$value"));
      }
    }
  }

  public function toXmlWithPrefix($node, $array, $prefix = ''): void {
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        $this->toXmlWithPrefix($node->addChild(is_numeric($key) ? 'item' : $key), $value);
      } else {
        $node->addChild($key, $value);
      }
    }
  }

  public function arrayToCsv($array, $csvFile): void {
    $f = fopen($csvFile, 'w');
    foreach ($array as $row) {
      fputcsv($f, $row, ';');
    }
    fclose($f);
  }

  function downloadCsv($array, $filename = "export.csv", $delimiter = ";"): void {
    $f = fopen('php://memory', 'w');
    foreach ($array as $line) {
      fputcsv($f, $line, $delimiter);
    }
    fseek($f, 0);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    fpassthru($f);
  }

  public function generateColor(): string {
    mt_srand((double)microtime() * 1000000);
    $colorCode = '';
    while (strlen($colorCode) < 6) {
      $colorCode .= sprintf("%02X", mt_rand(0, 255));
    }
    return '#' . $colorCode;
  }

  public function daysInYear($year): int {
    $days = 0;
    for ($month = 1; $month <= 12; $month++) {
      $days = $days + cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }
    return $days;
  }

  public function getMonthName($monthNumber): string {
    $arrayMonths = array(
        1 => 'Gennaio',
        2 => 'Febbraio',
        3 => 'Marzo',
        4 => 'Aprile',
        5 => 'Maggio',
        6 => 'Giugno',
        7 => 'Luglio',
        8 => 'Agosto',
        9 => 'Settembre',
        10 => 'Ottobre',
        11 => 'Novembre',
        12 => 'Dicembre'
    );
    return $arrayMonths[$monthNumber];
  }

  public function diffDateInDays($date1, $date2): bool|\DateInterval {
    $d1 = new \DateTime($date1);
    $d2 = new \DateTime($date2);
    $diff = $d1->diff($d2);
    return $diff->format('%r%a');
  }

  function getDaysInMonth($year, $month, $day = 'Monday'): array {

    $strDate = 'first ' . $day . ' of ' . $year . '-' . $month;

    $startDay = new \DateTime($strDate);

    $days = array();

    while ($startDay->format('Y-m') <= $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT)) {
      $days[] = clone($startDay);
      $startDay->modify('+ 7 days');
    }

    return $days;
  }

  function duplicaConWatermark($imgToCopy, $dir, $nomeFile, $text, $color = 'black', $fontSize = 20, $imageFormat = 'png'): void {
    $image = new Imagick($imgToCopy);
    $draw = new ImagickDraw();
    $draw->setFontSize($fontSize);
    $draw->setFillColor($color);
    $draw->setGravity(Imagick::GRAVITY_CENTER);
    $image->annotateImage($draw, 100, 12, 0, $text);
    $image->setImageFormat($imageFormat);
    $image->writeImage($dir . $nomeFile . '.' . $imageFormat);
  }

  public function checkDigit($valore): mixed {
    $codiceControllo = 0;
    $calcolaValore = 0;
    $ultimaCifra = 0;
    $multiploSuperiore = 0;

    $codice = str_replace('-', '', $valore);

    $indice = 1;
    foreach (str_split(strrev($codice)) as $n) {
      if ($indice % 2 == 0) {
        $calcolaValore += $n;
      } else {
        $calcolaValore += ($n * 3);
      }
      $indice++;
    }

    $ultimaCifra = substr($calcolaValore, -1);

    switch ($ultimaCifra) {
      case 0:
        $multiploSuperiore = $calcolaValore;
        break;
      case 1:
        $multiploSuperiore = $calcolaValore + 9;
        break;
      case 2:
        $multiploSuperiore = $calcolaValore + 8;
        break;
      case 3:
        $multiploSuperiore = $calcolaValore + 7;
        break;
      case 4:
        $multiploSuperiore = $calcolaValore + 6;
        break;
      case 5:
        $multiploSuperiore = $calcolaValore + 5;
        break;
      case 6:
        $multiploSuperiore = $calcolaValore + 4;
        break;
      case 7:
        $multiploSuperiore = $calcolaValore + 3;
        break;
      case 8:
        $multiploSuperiore = $calcolaValore + 2;
        break;
      case 9:
        $multiploSuperiore = $calcolaValore + 1;
        break;
    }

    $codiceControllo = $multiploSuperiore - $calcolaValore;

    return $codiceControllo;
  }

  public function formatDateUs($dd, $separator = '-'): string {
    $dnExplWn = explode($separator, $dd);
    return $dnExplWn[2] . '-' . $dnExplWn[1] . '-' . $dnExplWn[0];
  }

  public function isValidDate($date, $format = 'Y-m-d'): bool {
    $dateTime = \DateTime::createFromFormat($format, $date);
    return $dateTime && $dateTime->format($format) === $date;
  }

  public function creaTimeFile($file, $isFine) {
    $timeFile = fopen($file, "a");
    $txt = date('Y-m-d H:i:s') . "\r\n";
    if ($isFine) {
      $txt .= "-----\r\n";
    }
    fwrite($timeFile, $txt);
    fclose($timeFile);
  }
}
