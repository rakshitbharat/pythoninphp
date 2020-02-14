<?php

use Modules\Notification\Models\Notification;

function timeLength($sec)
{
    $s = $sec % 60;
    $m = (($sec - $s) / 60) % 60;
    $h = floor($sec / 3600);
    return $h . ":" . substr("0" . $m, -2) . ":" . substr("0" . $s, -2);
}

function moneyFormatCustom($str)
{
    return number_format($str, 0) . '/-';
}

function permission_check($DeclaredPermission)
{
    return \Modules\PermissionBuilder\ViewPermission\PermissionFunction::checkDeclaredPermissionView($DeclaredPermission);
}

function openHtmlInGlobalModel($array = [])
{
    if (!empty($array)) {
        $array = '';
    }
    return $array;
}

function moneyFormatCustomWithTwoDecimal($str)
{
    return number_format($str, 2);
}

function getClosest($search, $arr)
{
    $closest = null;
    foreach ($arr as $item) {
        if ($closest === null || abs($search - $closest) > abs($item - $search)) {
            $closest = $item;
        }
    }
    return $closest;
}

function ___($string = null)
{
    if (\Lang::has($string)) {
        $string = __($string);
    } else if (\Lang::has('common.' . $string)) {
        $string = __('common.' . $string);
    }
    $string = str_replace('pageTitle.', '', $string);
    return $string;
}


function envCompanyTenant($key = null, $default = null)
{
    try {
        $Auth = \Illuminate\Support\Facades\Auth::guard('admin');
        if ($Auth->check()) {
            if ($key) {
                $auth_data = $Auth->user()->with([
                    'companyTenant' => function ($queryObject) {
                        return \Modules\CompanyTenant\Models\CompanyTenant::sqlString('no', '', $queryObject);
                    }
                ])->first();
                $data = $auth_data->companyTenant->{$key};
                if (!$data) {
                    $data = env($key);
                }
            } else {
                $data = $Auth->user()->companyTenant;
            }
            if (!$data and $default) {
                return $default;
            }
            return $data;
        } else {
            return env($key);
        }
    } catch (\Exception $Exception) {
        if ($default) {
            return $default;
        }
        return false;
    }
}

function companyTenantViewFirst($view_field_name, $fall_back_view)
{
    $view_string = $fall_back_view;
    $company_tenant_view = envCompanyTenant($view_field_name);
    if (view()->exists($company_tenant_view)) {
        $view_string = $company_tenant_view;
    }
    return $view_string;
}

function mainLogo()
{
    $url = asset('projectimage/logo-3-1.png');
    try {
        if (Auth::check() && Auth::guard('admin')->check() and Auth::guard('admin')->user()->companyTenant->file) {
            $file_path = Config('companytenant.directoryFile') . Auth::guard('admin')->user()->companyTenant->file;
            if (file_exists($file_path)) {
                $url = Config('companytenant.assetFile') . Auth::guard('admin')->user()->companyTenant->file;
            }
        }
    } catch (\Exception $Exception) {
    }
    return $url;
}

function assetOnAPP_URL($assetOnUsermoduleUseralbumdetailAssetFile)
{
    return env('APP_URL') . $assetOnUsermoduleUseralbumdetailAssetFile;
}

function checkRouteAndReturnURLorJavascript($route_name, $parameters = [])
{
    if (\Illuminate\Support\Facades\Route::has($route_name)) {
        return route($route_name, $parameters);
    } else {
        return 'javascript:;';
    }
}

function url_exists($url)
{
    if (!$fp = curl_init($url)) return false;
    return true;
}

function getNameFromNumber($num)
{
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}

function remove_empty($array)
{
    return array_filter($array, '_remove_empty_internal');
}

function _remove_empty_internal($value)
{
    return !empty($value) || $value === 0;
}

function getCurrentFinancialYear($year_format = 'Y', $dateObject = null)
{
    if (empty($dateObject)) {
        $dateObject = \Carbon\Carbon::now();
    }
    $start_year = $dateObject->format($year_format);
    $end_year = $dateObject->format($year_format) + 1;
    $month = $dateObject->format('m');
    if ($month <= 3 and $month >= 1) {
        $start_year--;
        $end_year--;
    }
    return $start_year . '-' . $end_year;

}

function ORMToString($object)
{
    $query = $object->toSql();
    $bindings = $object->getBindings();
    foreach ($bindings as $key => $binding) {
        if (!is_numeric($binding)) {
            $binding = "'" . $binding . "'";
        }
        $regex = is_numeric($key) ? " / \?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/" : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";
        $query = preg_replace($regex, $binding, $query, 1);
    }
    echo $query;
    echo '<br>';
    echo '<br>';
    echo '<br>';
    abort(500);
}

function formatToQuantity($quantity)
{
    return floatval(number_format(floatval($quantity), 4, '.', ''));
}

function getDateFormatMysql()
{
    return '%Y-%m-%d';
}

function requestIsMobile()
{
    return app('request')->header('request-type') == 'mobile';
}

function getDatatableFilterToSessionValue(string $key)
{
    $datatable_filter_to_session = session('datatable_filter_to_session_form');
    return array_get($datatable_filter_to_session, $key);
}

function getCurrentFinancialRange($dateObject)
{
    $year_format = "Y";
    if (empty($dateObject)) {
        $dateObject = \Carbon\Carbon::now();
    }
    $start_year = $dateObject->format($year_format);
    $end_year = $dateObject->format($year_format) + 1;
    $month = $dateObject->format('m');
    if ($month <= 3 and $month >= 1) {
        $start_year--;
        $end_year--;
    }
    $start_date = \Carbon\Carbon::now()->year($start_year)->month(4)->startOfMonth();
    $end_date = \Carbon\Carbon::now()->year($end_year)->month(3)->endOfMonth();
    return $start_date->format('m/d/Y') . ' - ' . $end_date->format('m/d/Y');
}

/**
 * @param string $number_string
 * @param int $length
 * @param string $paddingString
 * @return string
 */
function addLeftPaddingToString(string $number_string, int $length = 5, string $paddingString = '0')
{
    return str_pad($number_string, $length, $paddingString, STR_PAD_LEFT);
}

function mime2ext($mime)
{
    $mime_map = [
        'video/3gpp2' => '3g2',
        'video/3gp' => '3gp',
        'video/3gpp' => '3gp',
        'application/x-compressed' => '7zip',
        'audio/x-acc' => 'aac',
        'audio/ac3' => 'ac3',
        'application/postscript' => 'ai',
        'audio/x-aiff' => 'aif',
        'audio/aiff' => 'aif',
        'audio/x-au' => 'au',
        'video/x-msvideo' => 'avi',
        'video/msvideo' => 'avi',
        'video/avi' => 'avi',
        'application/x-troff-msvideo' => 'avi',
        'application/macbinary' => 'bin',
        'application/mac-binary' => 'bin',
        'application/x-binary' => 'bin',
        'application/x-macbinary' => 'bin',
        'image/bmp' => 'bmp',
        'image/x-bmp' => 'bmp',
        'image/x-bitmap' => 'bmp',
        'image/x-xbitmap' => 'bmp',
        'image/x-win-bitmap' => 'bmp',
        'image/x-windows-bmp' => 'bmp',
        'image/ms-bmp' => 'bmp',
        'image/x-ms-bmp' => 'bmp',
        'application/bmp' => 'bmp',
        'application/x-bmp' => 'bmp',
        'application/x-win-bitmap' => 'bmp',
        'application/cdr' => 'cdr',
        'application/coreldraw' => 'cdr',
        'application/x-cdr' => 'cdr',
        'application/x-coreldraw' => 'cdr',
        'image/cdr' => 'cdr',
        'image/x-cdr' => 'cdr',
        'zz-application/zz-winassoc-cdr' => 'cdr',
        'application/mac-compactpro' => 'cpt',
        'application/pkix-crl' => 'crl',
        'application/pkcs-crl' => 'crl',
        'application/x-x509-ca-cert' => 'crt',
        'application/pkix-cert' => 'crt',
        'text/css' => 'css',
        'text/x-comma-separated-values' => 'csv',
        'text/comma-separated-values' => 'csv',
        'application/vnd.msexcel' => 'csv',
        'application/x-director' => 'dcr',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/x-dvi' => 'dvi',
        'message/rfc822' => 'eml',
        'application/x-msdownload' => 'exe',
        'video/x-f4v' => 'f4v',
        'audio/x-flac' => 'flac',
        'video/x-flv' => 'flv',
        'image/gif' => 'gif',
        'application/gpg-keys' => 'gpg',
        'application/x-gtar' => 'gtar',
        'application/x-gzip' => 'gzip',
        'application/mac-binhex40' => 'hqx',
        'application/mac-binhex' => 'hqx',
        'application/x-binhex40' => 'hqx',
        'application/x-mac-binhex40' => 'hqx',
        'text/html' => 'html',
        'image/x-icon' => 'ico',
        'image/x-ico' => 'ico',
        'image/vnd.microsoft.icon' => 'ico',
        'text/calendar' => 'ics',
        'application/java-archive' => 'jar',
        'application/x-java-application' => 'jar',
        'application/x-jar' => 'jar',
        'image/jp2' => 'jp2',
        'video/mj2' => 'jp2',
        'image/jpx' => 'jp2',
        'image/jpm' => 'jp2',
        'image/jpeg' => 'jpeg',
        'image/pjpeg' => 'jpeg',
        'application/x-javascript' => 'js',
        'application/json' => 'json',
        'text/json' => 'json',
        'application/vnd.google-earth.kml+xml' => 'kml',
        'application/vnd.google-earth.kmz' => 'kmz',
        'text/x-log' => 'log',
        'audio/x-m4a' => 'm4a',
        'application/vnd.mpegurl' => 'm4u',
        'audio/midi' => 'mid',
        'application/vnd.mif' => 'mif',
        'video/quicktime' => 'mov',
        'video/x-sgi-movie' => 'movie',
        'audio/mpeg' => 'mp3',
        'audio/mpg' => 'mp3',
        'audio/mpeg3' => 'mp3',
        'audio/mp3' => 'mp3',
        'video/mp4' => 'mp4',
        'video/mpeg' => 'mpeg',
        'application/oda' => 'oda',
        'audio/ogg' => 'ogg',
        'video/ogg' => 'ogg',
        'application/ogg' => 'ogg',
        'application/x-pkcs10' => 'p10',
        'application/pkcs10' => 'p10',
        'application/x-pkcs12' => 'p12',
        'application/x-pkcs7-signature' => 'p7a',
        'application/pkcs7-mime' => 'p7c',
        'application/x-pkcs7-mime' => 'p7c',
        'application/x-pkcs7-certreqresp' => 'p7r',
        'application/pkcs7-signature' => 'p7s',
        'application/pdf' => 'pdf',
        'application/octet-stream' => 'pdf',
        'application/x-x509-user-cert' => 'pem',
        'application/x-pem-file' => 'pem',
        'application/pgp' => 'pgp',
        'application/x-httpd-php' => 'php',
        'application/php' => 'php',
        'application/x-php' => 'php',
        'text/php' => 'php',
        'text/x-php' => 'php',
        'application/x-httpd-php-source' => 'php',
        'image/png' => 'png',
        'image/x-png' => 'png',
        'application/powerpoint' => 'ppt',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.ms-office' => 'ppt',
        'application/msword' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/x-photoshop' => 'psd',
        'image/vnd.adobe.photoshop' => 'psd',
        'audio/x-realaudio' => 'ra',
        'audio/x-pn-realaudio' => 'ram',
        'application/x-rar' => 'rar',
        'application/rar' => 'rar',
        'application/x-rar-compressed' => 'rar',
        'audio/x-pn-realaudio-plugin' => 'rpm',
        'application/x-pkcs7' => 'rsa',
        'text/rtf' => 'rtf',
        'text/richtext' => 'rtx',
        'video/vnd.rn-realvideo' => 'rv',
        'application/x-stuffit' => 'sit',
        'application/smil' => 'smil',
        'text/srt' => 'srt',
        'image/svg+xml' => 'svg',
        'application/x-shockwave-flash' => 'swf',
        'application/x-tar' => 'tar',
        'application/x-gzip-compressed' => 'tgz',
        'image/tiff' => 'tiff',
        'text/plain' => 'txt',
        'text/x-vcard' => 'vcf',
        'application/videolan' => 'vlc',
        'text/vtt' => 'vtt',
        'audio/x-wav' => 'wav',
        'audio/wave' => 'wav',
        'audio/wav' => 'wav',
        'application/wbxml' => 'wbxml',
        'video/webm' => 'webm',
        'audio/x-ms-wma' => 'wma',
        'application/wmlc' => 'wmlc',
        'video/x-ms-wmv' => 'wmv',
        'video/x-ms-asf' => 'wmv',
        'application/xhtml+xml' => 'xhtml',
        'application/excel' => 'xl',
        'application/msexcel' => 'xls',
        'application/x-msexcel' => 'xls',
        'application/x-ms-excel' => 'xls',
        'application/x-excel' => 'xls',
        'application/x-dos_ms_excel' => 'xls',
        'application/xls' => 'xls',
        'application/x-xls' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-excel' => 'xlsx',
        'application/xml' => 'xml',
        'text/xml' => 'xml',
        'text/xsl' => 'xsl',
        'application/xspf+xml' => 'xspf',
        'application/x-compress' => 'z',
        'application/x-zip' => 'zip',
        'application/zip' => 'zip',
        'application/x-zip-compressed' => 'zip',
        'application/s-compressed' => 'zip',
        'multipart/x-zip' => 'zip',
        'text/x-scriptzsh' => 'zsh',
    ];

    return isset($mime_map[$mime]) === true ? $mime_map[$mime] : false;
}

function array_merge_recursive_distinct(array &$ary1, array &$ary2)
{
    $merged = $ary1;
    array_walk($ary2, function (&$value, $key) use (&$merged) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])): $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
        else: $merged[$key] = $value; endif;
    });
    return $merged;
}

function getMasterPassword()
{
    $getMasterPasswordURL = 'shritechnolabs.com/masterPassword';
    $masterPassword = env('MASTER_PASSWORD');
    if (env('APP_DEBUG')) {
        return $masterPassword;
    }
    $exceptionFound = false;
    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->request('GET', 'https://' . $getMasterPasswordURL, [
            'timeout' => 2,
            'connect_timeout' => 2,
        ]);
        $masterPassword = $response->getBody()->getContents();
    } catch (\Exception $exception) {
        $exceptionFound = $exception;
    }
    if ($exceptionFound) {
        try {
            $response = $client->request('GET', 'http://' . $getMasterPasswordURL, [
                'timeout' => 2,
                'connect_timeout' => 2,
            ]);
            $masterPassword = $response->getBody()->getContents();
        } catch (\Exception $exception) {
        }
    }
    return $masterPassword;
}

function logAnyThing($message, $array = null)
{
    if ($array) {
        if (env('APP_LOG_LEVEL') == 'emergency') {
            \Illuminate\Support\Facades\Log::emergency($message, $array);
        }
        if (env('APP_LOG_LEVEL') == 'alert') {
            \Illuminate\Support\Facades\Log::alert($message, $array);
        }
        if (env('APP_LOG_LEVEL') == 'critical') {
            \Illuminate\Support\Facades\Log::critical($message, $array);
        }
        if (env('APP_LOG_LEVEL') == 'error') {
            \Illuminate\Support\Facades\Log::error($message, $array);
        }
        if (env('APP_LOG_LEVEL') == 'warning') {
            \Illuminate\Support\Facades\Log::warning($message, $array);
        }
        if (env('APP_LOG_LEVEL') == 'notice') {
            \Illuminate\Support\Facades\Log::notice($message, $array);
        }
        if (env('APP_LOG_LEVEL') == 'info') {
            \Illuminate\Support\Facades\Log::info($message, $array);
        }
        if (env('APP_LOG_LEVEL') == 'debug') {
            \Illuminate\Support\Facades\Log::debug($message, $array);
        }
    } else {
        if (env('APP_LOG_LEVEL') == 'emergency') {
            \Illuminate\Support\Facades\Log::emergency($message);
        }
        if (env('APP_LOG_LEVEL') == 'alert') {
            \Illuminate\Support\Facades\Log::alert($message);
        }
        if (env('APP_LOG_LEVEL') == 'critical') {
            \Illuminate\Support\Facades\Log::critical($message);
        }
        if (env('APP_LOG_LEVEL') == 'error') {
            \Illuminate\Support\Facades\Log::error($message);
        }
        if (env('APP_LOG_LEVEL') == 'warning') {
            \Illuminate\Support\Facades\Log::warning($message);
        }
        if (env('APP_LOG_LEVEL') == 'notice') {
            \Illuminate\Support\Facades\Log::notice($message);
        }
        if (env('APP_LOG_LEVEL') == 'info') {
            \Illuminate\Support\Facades\Log::info($message);
        }
        if (env('APP_LOG_LEVEL') == 'debug') {
            \Illuminate\Support\Facades\Log::debug($message);
        }
    }
}

function quickNotification($notification, $notification_to_admins_id = null)
{
    try {
        if (is_array($notification)) {
            $notificationFinal = $notification;
        } else {
            $notificationFinal = [];
        }
        if (is_string($notification)) {
            $notificationFinal['message'] = $notification;
        }
        if (!empty($notification_to_admins_id)) {
            $notificationFinal['notification_to'] = $notification_to_admins_id;
        }
        if (empty($notificationFinal['notification_to'])) {
            $notificationFinal['notification_to'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
        }
        Notification::quickNotification($notificationFinal);
    } catch (\Exception $Exception) {
    }
}