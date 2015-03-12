<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Curl\Curl;

define('PUT_BASE_DIR', __DIR__ . '/../data/phone/jp/others');

// 総務省の電話番号リストの在りか
$excels = [
    'http://www.soumu.go.jp/main_content/000124112.xls',    // 0120
    'http://www.soumu.go.jp/main_content/000124114.xls',    // 0800
    'http://www.soumu.go.jp/main_content/000124113.xls',    // 0570
    'http://www.soumu.go.jp/main_content/000124118.xls',    // 0990
];

foreach ($excels as $url) {
    $spreadsheet = parseExcel(downloadExcel($url));
    list($start, $data) = convertSheet($spreadsheet->getActiveSheet());
    saveData($start, $data);
}

function downloadExcel($url)
{
    echo "Downloading $url ...\n";
    $curl = new Curl();
    $curl->get($url);
    if ($curl->error) {
        throw new Exception('Could not download ' . $url);
    }
    return $curl->raw_response;
}

function parseExcel($binary)
{
    echo "Parsing Excel...\n";
    $tmppath = tempnam(sys_get_temp_dir(), 'xls-');
    try {
        file_put_contents($tmppath, $binary);
        $reader = PHPExcel_IOFactory::createReader('Excel5');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($tmppath);
        @unlink($spreadsheet);
        return $spreadsheet;
    } catch (Exception $e) {
        @unlink($tmppath);
        throw $e;
    }
}

function convertSheet($sheet)
{
    echo "Converting...\n";
    $key = null;
    $ret = [];
    $rowCount = $sheet->getHighestRow();
    for ($y = 1; $y <= $rowCount; ++$y) {
        if (preg_match('/^0\d+$/', $sheet->getCell("A{$y}")->getValue())) {
            break;
        }
    }
    for (; $y <= $rowCount; ++$y) {
        $prefix = trim($sheet->getCell("A{$y}")->getValue());
        for ($x = 0; $x <= 9; ++$x) {
            $cell = chr(ord('B') + $x) . $y;
            if (trim($sheet->getCell($cell)->getValue()) !== '') {
                $number = $prefix . (string)$x;
                if ($key === null) {
                    $key = substr($number, 0, 4);
                }
                $ret[] = substr($number, 4);
            }
        }
    }
    return [$key, $ret];
}

function saveData($startdigit, $data)
{
    $filepath = PUT_BASE_DIR . '/' . $startdigit . '.json.gz';
    if (!file_exists(dirname($filepath))) {
        mkdir(dirname($filepath), 0755, true);
    }
    $json = json_encode($data);
    file_put_contents($filepath, gzencode($json, 9, FORCE_GZIP));
}
