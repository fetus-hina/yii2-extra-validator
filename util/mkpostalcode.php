<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Curl\Curl;

define('PUT_BASE_DIR', __DIR__ . '/../data/postalcode/jp');

$sources = [
    'http://www.post.japanpost.jp/zipcode/dl/oogaki/zip/ken_all.zip' => [ 'KEN_ALL.CSV', 2 ],
    'http://www.post.japanpost.jp/zipcode/dl/jigyosyo/zip/jigyosyo.zip' => [ 'JIGYOSYO.CSV', 7 ],
];

$data = [];
foreach ($sources as $url => $parseInfo) {
    $tmpData = parseCsv(
        downloadCsv($url, $parseInfo[0]),
        $parseInfo[1]
    );
    foreach ($tmpData as $zip1 => $list) {
        if (!isset($data[$zip1])) {
            $data[$zip1] = [];
        }
        $data[$zip1] = array_merge($data[$zip1], $list);
    }
}

foreach ($data as $zip1 => $list) {
    printf("save %03d ...\n", $zip1);
    usort(
        $list,
        function ($lhs, $rhs) {
            return (int)$lhs - (int)$rhs;
        }
    );
    save(sprintf('%03d', $zip1), $list);
}

function downloadCsv($url, $filename)
{
    echo "Downloading $url ...\n";
    $curl = new Curl();
    $curl->get($url);
    if ($curl->error) {
        throw new Exception('Could not download ' . $url);
    }

    echo "Extracting $filename ...\n";
    $tmppath = tempnam(sys_get_temp_dir(), 'zip-');
    try {
        file_put_contents($tmppath, $curl->raw_response);
        $zip = new ZipArchive();
        if ($zip->open($tmppath, 0) !== true) {
            throw new Exception('Could not open zip archive');
        }
        $csv = $zip->getFromName($filename);
        $zip->close();
        if ($csv === false) {
            throw new Exception('Could not extract ' . $filename . ' from archive');
        }
        @unlink($tmppath);
        return $csv;
    } catch (Exception $e) {
        @unlink($tmppath);
        throw $e;
    }
}

function parseCsv($csv, $pos)
{
    $ret = [];

    echo "Parsing CSV...\n";
    $tmppath = tempnam(sys_get_temp_dir(), 'csv-');
    try {
        echo "  save tmp file...\n";
        file_put_contents($tmppath, mb_convert_encoding($csv, 'UTF-8', 'CP932'));

        echo "  parse...\n";
        $fh = fopen($tmppath, 'rt');
        while (!feof($fh)) {
            $line = fgetcsv($fh);
            if (@preg_match('/^\d{7}$/', $line[$pos])) {
                $zip1 = substr($line[$pos], 0, 3);
                $zip2 = substr($line[$pos], 3, 4);
                if (!isset($ret[$zip1])) {
                    $ret[$zip1] = [];
                }
                $ret[$zip1][] = $zip2;
            }
        }
        fclose($fh);
        @unlink($tmppath);
        return $ret;
    } catch (Exception $e) {
        @unlink($tmppath);
        throw $e;
    }
}

function save($zip1, $zip2list)
{
    $filepath = PUT_BASE_DIR . '/' . $zip1 . '.json.gz';
    if (!file_exists(dirname($filepath))) {
        mkdir(dirname($filepath), 0755, true);
    }
    $json = json_encode($zip2list);
    file_put_contents($filepath, gzencode($json, 9, FORCE_GZIP));
}
