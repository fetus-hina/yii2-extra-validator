<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.1
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators;

use yii\validators\UrlValidator;

/**
 * the attribute value is a valid http or https URL.
 */
class StrictUrlValidator extends UrlValidator
{
    public $pattern = null;

    /**
     * @inheritdoc
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->pattern === null) {
            $this->pattern = $this->makeUrlRegex();
        }
    }

    /**
     * make the exact URL regex.
     *
     * import from http://www.din.or.jp/~ohzaki/perl.htm#httpURL
     */
    private function makeUrlRegex(): string
    {
        $digit = '[0-9]';
        $alpha = '[a-zA-Z]';
        $alnum = '[a-zA-Z0-9]';
        $hex = '[0-9A-Fa-f]';
        $escaped = "%{$hex}{$hex}";
        $uric = '(?:[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,]' . "|{$escaped})";
        $fragment = "{$uric}*";
        $query = "{$uric}*";
        $pchar = '(?:[-_.!~*\'()a-zA-Z0-9:@&=+$,]' . "|{$escaped})";
        $param = "{$pchar}*";
        $segment = "{$pchar}*(?:;{$param})*";
        $pathSegments = "{$segment}(?:/{$segment})*";
        $absPath = "/{$pathSegments}";
        $port = "{$digit}*";
        $ipv4Address = "{$digit}+\\.{$digit}+\\.{$digit}+\\.{$digit}+";
        $topLabel = "{$alpha}(?:" . '[-a-zA-Z0-9]*' . "{$alnum})?";
        $domainLabel = "{$alnum}(?:" . '[-a-zA-Z0-9]*' . "{$alnum})?";
        $hostName = "(?:{$domainLabel}\\.)*{$topLabel}\\.?";
        $host = "(?:{$hostName}|{$ipv4Address})";
        $hostPort = "{$host}(?::{$port})?";
        $userInfo = '(?:[-_.!~*\'()a-zA-Z0-9;:&=+$,]|' . "{$escaped})*";
        $server = "(?:{$userInfo}\@)?{$hostPort}";
        $authority = "{$server}";
        $scheme = '{schemes}';
        $netPath = "//{$authority}(?:{$absPath})?";
        $hierPart = "{$netPath}(?:\\?{$query})?";
        $absoluteUri = "{$scheme}:{$hierPart}";
        $uriReference = "{$absoluteUri}(?:#{$fragment})?";
        return "`^{$uriReference}$`";
    }
}
