<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Curl\Curl;

define('PUT_BASE_DIR', __DIR__ . '/../data/phone/jp/landline');

// 総務省の電話番号リストの在りか
$excels = [
    'http://www.soumu.go.jp/main_content/000124070.xls',    // 01...
    'http://www.soumu.go.jp/main_content/000124071.xls',    // 02...
    'http://www.soumu.go.jp/main_content/000124072.xls',    // 03...
    'http://www.soumu.go.jp/main_content/000124073.xls',    // 04...
    'http://www.soumu.go.jp/main_content/000124074.xls',    // 05...
    'http://www.soumu.go.jp/main_content/000124075.xls',    // 06...
    'http://www.soumu.go.jp/main_content/000124076.xls',    // 07...
    'http://www.soumu.go.jp/main_content/000124077.xls',    // 08...
    'http://www.soumu.go.jp/main_content/000124078.xls',    // 09...
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
        if ($sheet->getCell("G{$y}")->getValue() === '使用中') {
            $shigai = $sheet->getCell("D{$y}")->getValue();
            $shinai = $sheet->getCell("E{$y}")->getValue();
            if ($key === null) {
                $key = substr($shigai, 0, 2);
            }
            if (!isset($ret["_{$shigai}"])) {
                echo "  市外局番: {$shigai}\n";
                $ret["_{$shigai}"] = [];
            }
            $ret["_{$shigai}"][] = $shinai;
        }
    }
    return [$key, $ret];
}

function saveData($start2digit, $data)
{
    $filepath1 = PUT_BASE_DIR . '/' . $start2digit . '.json.gz';
    if (!file_exists(dirname($filepath1))) {
        mkdir(dirname($filepath1), 0755, true);
    }
    $json = json_encode(array_map(
        function ($shigai) {
            return ltrim($shigai, '_');
        },
        array_keys($data)
    ));
    file_put_contents($filepath1, gzencode($json, 9, FORCE_GZIP));
    foreach ($data as $shigai_ => $shinaiList) {
        $shigai = ltrim($shigai_, '_');
        $filepath2 = PUT_BASE_DIR . '/' . $start2digit . '/' . $shigai . '.json.gz';
        if (!file_exists(dirname($filepath2))) {
            mkdir(dirname($filepath2), 0755, true);
        }
        $json = json_encode($shinaiList);
        file_put_contents($filepath2, gzencode($json, 9, FORCE_GZIP));
    }
}
