<?php

/**
 * UpgradeMODXWidget snippet for UpgradeMODX extra
 *
 * Copyright 2015-2018 Bob Ray <https://bobsguides.com>
 * Created on 08-16-2015
 *
 * UpgradeMODX is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * UpgradeMODX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * UpgradeMODX; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package upgrademodx
 */


/*
 * This package was inspired by the work of a number of people and I have borrowed some of their code.
 * Dmytro Lukianenko (dmi3yy) is the original author of the MODX install script. Susan Sottwell,
 * Sharapov, Bumkaka, Inreti, Zaigham Rana, frischnetz, and AgelxNash, also contributed and I'd
 * like to thank all of them for laying the groundwork.
  */

error_reporting(0);
ini_set('display_errors', 0);
set_time_limit(0);
ini_set('max_execution_time', 0);
header('Content-Type: text/html; charset=utf-8');

if (extension_loaded('xdebug')) {
    ini_set('xdebug.max_nesting_level', 100000);
}


class MODXInstaller {
    static public function downloadFile($url, $path, $method, $certPath)
    {
        $newfname = $path;
        if (file_exists($path)) {
            unlink($path);
        }
        $newf = null;
        $file = null;
        if ($method == 'fopen') {
            try {
                $file = fopen($url, "rb");
                if ($file) {
                    $newf = fopen($newfname, "wb");
                    if ($newf) {
                        set_time_limit(0);
                        while (!feof($file)) {
                            fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                        }
                    } else {
                        return ('Could not open ' . $newf . ' for writing');
                    }
                } else {
                    return ('fopen failed to open ' . $url);
                }
            } catch (Exception $e) {
                return 'ERROR:Download ' . $e->getMessage();
            }
            if ($file) {
                fclose($file);
            }
            if ($newf) {
                fclose($newf);
            }

        } elseif ($method == 'curl') {
            $newf = fopen($path, "wb");
            if ($newf) {
                set_time_limit(0);
                $ch = curl_init(str_replace(" ", "%20", $url));
                curl_setopt($ch, CURLOPT_CAINFO, $certPath);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 180);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0)');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FILE, $newf);
                $openBasedir = ini_get('open_basedir');
                if (empty($openBasedir) && filter_var(ini_get('safe_mode'),
                        FILTER_VALIDATE_BOOLEAN) === false) {
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                } else {
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                    $rch = curl_copy_handle($ch);
                    curl_setopt($rch, CURLOPT_URL, $url);
                    $header = curl_exec($rch);
                    if (!curl_errno($rch)) {
                        $newurl = $url;
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                        if ($code == 301 || $code == 302) {
                            if (version_compare(PHP_VERSION, '5.3.7') < 0) {
                                preg_match('/Location:(.*?)\n/i', $header, $matches);
                                $newurl = trim(array_pop($matches));
                            } else {
                                $newurl = curl_getinfo($rch, CURLINFO_REDIRECT_URL);
                            }
                        }
                        curl_close($rch);
                        curl_setopt($ch, CURLOPT_URL, $newurl);
                    }
                }
                $retVal = curl_exec($ch);
                if ($retVal === false) {

                    return ('cUrl download of modx.zip failed ' . curl_error($ch));
                }
                curl_close($ch);
            } else {
                return ('Cannot open ' . $path . ' for writing');
            }
        } else {
            return 'Invalid method in call to downloadFile()';
        }

        return true;
    }

    static public function removeFolder($path, $removeRoot = true) {
        $dir = realpath($path);
        if (!is_dir($dir)) {
            return;
        }
        $it = new RecursiveDirectoryIterator($dir);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        if ($removeRoot) {
            rmdir($dir);
        }
    }

    static public function copyFolder($src, $dest) {

        $path = realpath($src);
        $dest = realpath($dest);
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $name => $object) {
            $startsAt = substr(dirname($name), strlen($path));
            self::mmkDir($dest . $startsAt, true);
            if ($object->isDir()) {
                self::mmkDir($dest . substr($name, strlen($path)));
            }

            if (is_writable($dest . $startsAt) and $object->isFile()) {
                copy((string)$name, $dest . $startsAt . DIRECTORY_SEPARATOR . basename($name));
            }
        }
    }

    static public function normalize($paths) {
        if (is_array($paths)) {
            foreach ($paths as $k => $v) {
                $v = str_replace('\\', '/', rtrim($v, '/\\'));
                $paths[$k] = $v;
            }
        } else {
            $paths = str_replace('\\', '/', rtrim($paths, '/\\'));
        }
        return $paths;
    }

    static public function getDirectories($directories = array()) {
        if (empty($directories)) {
            $directories = array(
                'setup' => MODX_BASE_PATH . 'setup',
                'core' => MODX_CORE_PATH,
                'manager' => MODX_MANAGER_PATH,
                'connectors' => MODX_CONNECTORS_PATH,
            );
        }
        /* See if we need to do processors path */
        $modxProcessorsPath = MODXInstaller::normalize(MODX_PROCESSORS_PATH);
        if (strpos(MODX_PROCESSORS_PATH, 'core/model/modx/processors') === false) {
            $directories['core/model/modx/processors'] = $modxProcessorsPath;
        }

        /* Normalize directory paths */
        $directories = MODXInstaller::normalize($directories);

        return $directories;

    }

    static public function copyFiles($sourceDir, $directories) {

        /* Normalize directory paths */
        MODXInstaller::normalize($directories);
        MODXInstaller::normalize($sourceDir);

        /* Copy directories */
        foreach ($directories as $source => $target) {
            MODXInstaller::mmkDir($target);
            set_time_limit(0);
            MODXInstaller::copyFolder($sourceDir . '/' . $source, $target);
        }

    }

    static public function mmkDir($folder, $perm = 0755) {
        if (!is_dir($folder)) {
            $oldumask = umask(0);
            mkdir($folder, $perm, true);
            umask($oldumask);
        }
    }
    
    static public function unZip($corePath, $source, $destination, $forcePclZip = false) {
        $status = true;
        if ( (! $forcePclZip) && class_exists('ZipArchive', false)) {
            $zip = new ZipArchive;
            if ($zip instanceof ZipArchive) {
                $open = $zip->open($source, ZIPARCHIVE::CHECKCONS);

                if ($open == true) {
                    $result = $zip->extractTo($destination);
                    if ($result === false) {
                         /* Yes, this is fucking nuts, but it's necessary on some platforms */
                         $result = $zip->extractTo($destination);
                         if ($result === false) {
                             $msg = $zip->getStatusString();
                             MODXInstaller::quit($msg);
                         }
                    }
                    $zip->close();
                } else {
                    $status = 'Could not open ZipArchive ' . $source . ' ' . $zip->getStatusString();
                }

            } else {
                $status = 'Could not instantiate ZipArchive';
            }
        } else {
            $zipClass = $corePath . 'xpdo/compression/pclzip.lib.php';
            if (file_exists($zipClass)) {
                include $corePath . 'xpdo/compression/pclzip.lib.php';
                $archive = new PclZip($source);
                if ($archive->extract(PCLZIP_OPT_PATH, $destination) == 0) {
                    $status = 'Extraction with PclZip failed - Error : ' . $archive->errorInfo(true);
                }
            } else {
                $status = 'Neither ZipArchive, nor PclZip were available to unzip the archive';
            }
        }
        return $status;
    }


    /**
     * Get name of downloaded MODX directory (e.g., modx-3.4.0-pl).
     *
     * @param $tempDir string - temporary download directory
     * @return string - Name of directory
     */
    public static function getModxDir($tempDir) {
        $handle = opendir($tempDir);
        if ($handle !== false) {
            while (false !== ($name = readdir($handle))) {
                if ($name != "." && $name != "..") {
                    $dir = $name;
                }
            }
            closedir($handle);
        } else {
            MODXInstaller::quit ('Unable to read directory contents or directory is empty: ' . dirname(__FILE__) . '/temp');
        }

        if (empty($dir)) {
            MODXInstaller::quit('Unknown error reading /temp directory');
        }

        return $dir;
    }

    public static function quit($msg) {
        $begin = '<div style="margin:auto;margin-top:100px;width:40%;height:80px;padding:30px;color:red;border:3px solid darkgray;text-align:center;background-color:rgba(160, 233, 174, 0.42);border-radius:15px;box-shadow: 10px 10px 5px #888888;"><p style="font-size: 14pt;">';
        $end = '</p><p style="margin-bottom:120px;"><a href="' . MODX_MANAGER_URL . '">Back to Manager</a></p></div>';
        die($begin . $msg  . $end);
    }
}

/* Do not touch the following comments! You have been warned!  */
/** @var $forcePclZip bool - force the use of PclZip instead of ZipArchive */
/* [[+ForcePclZip]] */
/* [[+ForceFopen]] */
/* [[+InstallData]] */

$method = 0;
if (extension_loaded('curl') && (!$forceFopen)) {
    $method = 'curl';
} elseif (ini_get('allow_url_fopen')) {
    $method = 'fopen';
}

/* Next two lines for running in debugger  */
// if (true || !empty($_GET['modx']) && is_scalar($_GET['modx']) && isset($InstallData[$_GET['modx']])) {
//       $rowInstall = $InstallData['revo2.4.1-pl'];
// Comment our the two lines below to run in debugger.


   if (!empty($_GET['modx']) && is_scalar($_GET['modx']) && isset($InstallData[$_GET['modx']])) {
       $rowInstall = $InstallData[$_GET['modx']];

    if (file_exists('config.core.php')) {
        @include 'config.core.php';
    }
    if (!defined('MODX_CORE_PATH')) {
        MODXInstaller::quit('Could not read config.core.php');
    }

    @include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';

    if (!defined('MODX_CONNECTORS_PATH')) {
        MODXInstaller::quit ('Could not read main config file');
    }


    /* run unzip and install */
    $source = dirname(__FILE__) . "/modx.zip";
    $url = $rowInstall['link'];
    $certPath = MODX_CORE_PATH . 'components/upgrademodx/cacert.pem';
    if (! file_exists($certPath)) {
        MODXInstaller::quit('Could not find cacert.pem');
    }
    set_time_limit(0);

    $success = MODXInstaller::downloadFile($url, $source, $method, $certPath);

    /* Make sure we have the downloaded file */

    if ($success !== true) {
        MODXInstaller::quit($success);
    } elseif (!file_exists($source)) {
            MODXInstaller::quit ('Missing file: ' . $source);
    } elseif (filesize($source) < 64) {
        MODXInstaller::quit('File: ' . $source . ' is empty -- download failed');
    }

    $tempDir = realPath(dirname(__FILE__)) . '/ugmtemp';
    MODXInstaller::mmkdir($tempDir);
    clearstatcache();

    $destination = $tempDir;

    if (! file_exists($tempDir)) {
        MODXInstaller::quit('Unable to create directory: ' . $tempDir);
    }

    if (! is_readable($tempDir)) {
        MODXInstaller::quit('Unable to read from /ugmtemp directory');
    }
    set_time_limit(0);
    $success = MODXInstaller::unZip(MODX_CORE_PATH, $source, $destination, $forcePclZip);
    if ($success !== true) {
        MODXInstaller::quit($success);
    }


    $directories = MODXInstaller::getDirectories();

    $directories = MODXInstaller::normalize($directories);

    $sourceDir = $tempDir . '/' . MODXInstaller::getModxDir($tempDir);
    $sourceDir = MODXInstaller::normalize($sourceDir);

    MODXInstaller::copyFiles($sourceDir, $directories);

    unlink($source);

    if (! is_dir(MODX_BASE_PATH . 'setup')) {
        MODXInstaller::quit('File Copy Failed');
    }

    MODXInstaller::removeFolder($tempDir, true);

    /* Clear cache files but not cache folder */

    $path = MODX_CORE_PATH . 'cache';
    if (is_dir($path)) {
        MODXInstaller::removeFolder($path, false);
    }

    unlink(basename(__FILE__));

    /* Log upgrade in Manager Actions log */
    include MODX_CORE_PATH . 'model/modx/modx.class.php';

    $modx = new modX();
    $modx->initialize('web');
    $modx->lexicon->load('core:default');
    $modx->logManagerAction('Upgrade MODX','modWorkspace', $modx->lexicon('version') . ' ' . $_GET['modx'], $_GET['userId'] );
    $modx = null;

    /* Forward to Setup */
    header('Location: ' . $rowInstall['location']);

} else {
    $ItemGrid = array();
    foreach ($InstallData as $ver => $item) {
        $ItemGrid[$item['tree']][$ver] = $item;
    }

/* Display the Form */
    echo '
<!DOCTYPE html>
<html>
<head>
    <title>UpgradeMODX</title>
    <meta charset="utf-8">
    <style type="text/css">
        @import url("https://fonts.googleapis.com/css?family=PT+Serif:400,700&subset=latin,cyrillic");article,aside,audio,b,body,canvas,dd,details,div,dl,dt,em,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,img,label,li,mark,menu,nav,ol,p,section,span,strong,summary,table,tbody,td,tfoot,th,thead,time,tr,u,ul,video{margin:0;padding:0;border:0;outline:0;vertical-align:baseline;background:0 0;font-size:100%}a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:0 0}table{border-collapse:collapse;border-spacing:0}td,td img{vertical-align:top}button,input,select,textarea{margin:0;font-size:100%}input[type=password],input[type=text],textarea{padding:0}input[type=checkbox]{vertical-align:bottom}input[type=radio]{vertical-align:text-bottom}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}html{overflow-y:scroll}body{color:#111;text-align:left;font:12px Verdana,"Geneva CY","DejaVu Sans",sans-serif}button,input,select,textarea{font-family:Verdana,"Geneva CY","DejaVu Sans",sans-serif}a,a:active,a:focus,a:hover,a:visited,button,input[type=button],input[type=submit],label{cursor:pointer}::selection{background:#84d5e8;color:#fff;text-shadow:none}html{position:relative;background:#f8f8f8 url("[[++assets_url]]components/upgrademodx/images/base.png")}body{background:0 0;font-size:14px;line-height:22px;font-family:"Helvetica Neue",helvetica,arial,sans-serif;text-shadow:0 1px 0 #fff}a{color:#0f7096}.button,button{color:#fff;display:inline-block;padding:15px;font-size:20px;text-decoration:none;border:5px solid #fff;border-radius:8px;background-color:#67a749;background-image:linear-gradient(to top,#67a749 0,#67a749 27.76%,#a1c755 100%);text-shadow:0 0 2px rgba(0,0,0,.64)}a.button{padding:5px 15px}h1,h2,h3,h4,h5{font-family:"PT Serif",helvetica,arial,sans-serif;line-height:28px}h1{font-size:26px}h2{font-size:22px}h3{font-size:18px}h4{font-size:16px}h5{font-size:14px}.header{-moz-box-sizing: border-box;float:left;width:100%;box-sizing:border-box;background:#fff;background:linear-gradient(to bottom,#fff,#f2f2f2);padding:20px;border-bottom:1px solid #fff}.header img{float:left;width:180px;margin:0 5px 0 0}.header h1.main-heading{color:#137899;font-size:32px;line-height:40px}.header-button-wrapper{float:right}.main-heading>span{display:none}.main-heading>sup{color:#ccc;font-weight:400}.content{float:left;padding:30px}.content h2{margin:0;line-height:20px}.content form{margin:10px 0 50px}.content form .column{float:left;box-sizing:border-box;width:500px;margin:20px 0}.column h3{display:inline-block;padding:0 0 5px;margin:0 0 20px;border-bottom:2px solid #000}.column label{float:left;width:100%;clear:both;padding:3px 0}form button{float:left;width:200px;clear:both}label>span{border-bottom:1px dotted #555}label>input{margin:0 5px 0 0}.footer{position:absolute;bottom:20px;right:20px;font-size:10px;color:#ccc}.footer a{color:#aaa}
    </style>
</head>
<body>
    <div class="header">
        <img src="https://modx.com/assets/i/logos/v5/svgs/modx-color.svg" alt="Logo">
        <h1 class="main-heading"><span>MODX</span> UpgradeMODX </h1>
        <div class="header-button-wrapper">
            <a href="https://github.com/BobRay/UpgradeMODX" class="button">GitHub</a>
        </div>
    </div>

<div class="content">';
    echo "\n" .  '<h2>Choose MODX version for Upgrade</h2>
    <form>';
    foreach ($ItemGrid as $tree => $item) {
        echo "\n" . '<div class="column">' .
            "\n<h3>" . strtoupper($tree) . '</h3>';
        foreach ($item as $version => $itemInfo) {
            echo "\n    " . '<label><input type="radio" name="modx" value="' . $version . '">            <span>' . $itemInfo['name'] . '</span></label><br>';
        }
        echo '</div>';
    }
    echo "\n    " . '<input type="hidden" name="userId" value="[[+modx.user.id]]">';
    if ($method) {
        echo "\n<h2> Using " . $method . "</h2>";
        echo "\n" . '<br><button>Upgrade &rarr;</button>';
    } else {
        echo "\n" . '<h2>Cannot download the files - neither cURL nor allow_url_fopen is enabled on this server.</h2>';
    }
    echo '</form>' . "\n " .
'</div>
    <div class="footer">
        <p>Originally created by <a href="//ga-alex.com" title="">Bumkaka</a> & <a href="//dmi3yy.com" title="">Dmi3yy</a></p>
        <p>Modified for Revolution only by <a href="//sottwell.com" title="">sottwell</a></p>
        <p>Modified for Upgrade only with dashboard widget by <a href="//bobsguides.com" title="">BobRay</a></p>
        <p>Designed by <a href="//a-sharapov.com" title="">Sharapov</a></p>
    </div>
</body>
</html>
';
}
?>
