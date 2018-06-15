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
ini_set("zlib.output_compression", 0);  // off
ini_set("implicit_flush", 1);  // on

if (extension_loaded('xdebug')) {
    ini_set('xdebug.max_nesting_level', 100000);
}

/* ToDo: Use placeholders for lex strings */

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
            if ($newf) {
                fclose($newf);
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

    public static function updateProgress($path, $content) {
        return file_put_contents($path, $content, LOCK_EX);
    }
    public static function createVersionForm($InstallData, $submitted) {
        $output = '';

        if ($submitted) { // just return button
            $output .= <<<EOD
               <div class="container">
                    <!-- Top Navigation -->
        
                    <div class="wrapper">
                            <button id="ugm_submit_button" class="progress-button" data-style="rotate-angle-bottom" data-perspective data-horizontal><span id="button_content" class="content">Begin Upgrade</span></button>
                    </div>
        
                </div><!-- /container -->
EOD;
            return $output;
        }

        $ItemGrid = array();
        foreach ($InstallData as $ver => $item) {
            $ItemGrid[$item['tree']][$ver] = $item;
        }

        $output .= "\n    <form>";
        foreach ($ItemGrid as $tree => $item) {
            $output .= "\n" . '<div class="column">' .
                "\n<h3>" . strtoupper($tree) . '</h3>';
            $output .= "<br><p style='font-size:125%;'><b>Important:</b> It is <i>strongly</i> recommended that you install all of the versions ending in .0 between your version and the current version of MODX.</p><br>";
            foreach ($item as $version => $itemInfo) {
                $selected = $itemInfo['selected'] ? ' checked' : '';
                $current = $itemInfo['current'] ? ' &nbsp;&nbsp;(current version)' : '';

                $output .= <<<EOD
        <label><input type="radio"{$selected} name="modx" value="$version">
            <span>{$itemInfo['name']} $current</span>
        </label>
<br>
EOD;

            } // end inner foreach loop
            $output .= '</div>';
        } // end outer foreach loop
        $output .= "\n    " . '<input type="hidden" name="userId" value="[[+modx.user.id]]">';
        // $output .= "\n" . '<br><button id="ugm_submit_button">Upgrade</button>';
        $output .= <<<EOD
       <div class="container">
            <!-- Top Navigation -->

            <div class="wrapper">
                    <button id="ugm_submit_button" class="progress-button" data-style="rotate-angle-bottom" data-perspective data-horizontal><span id = "button_content" class="content">Begin Upgrade</span></button>
            </div>

        </div><!-- /container -->
EOD;


        $output .= '</form>' . "\n ";
        return $output;
    }

    public static function quit($msg) {
        $begin = '<div style="margin:auto;margin-top:100px;width:40%;height:80px;padding:30px;color:red;border:3px solid darkgray;text-align:center;background-color:rgba(160, 233, 174, 0.42);border-radius:15px;box-shadow: 10px 10px 5px #888888;"><p style="font-size: 14pt;">';
        $end = '</p><p style="margin-bottom:120px;"><a href="' . MODX_MANAGER_URL . '">Back to Manager</a></p></div>';
        die($begin . $msg  . $end);
    }
} /* End of MODXInstaller class */

/* Do not touch the following comments! You have been warned!  */
/** @var $forcePclZip bool - force the use of PclZip instead of ZipArchive */
/* [[+ForcePclZip]] */
/* [[+ForceFopen]] */
/* [[+InstallData]] */

$method = 0;
$output = '';
$submitted = !empty($_GET['modx']) && is_scalar($_GET['modx']) && isset($InstallData[$_GET['modx']]);

if (extension_loaded('curl') && (!$forceFopen)) {
    $method = 'curl';
} elseif (ini_get('allow_url_fopen')) {
    $method = 'fopen';
}

$output .= <<<EOD
<!DOCTYPE html>
<html>
<head>
    <title>UpgradeMODX</title>
    <meta charset="utf-8">
    
     <link rel="stylesheet" href="http://localhost/addons/assets/mycomponents/upgrademodx/_build/test/3d/css/progress.css"/>
    <style>
      @import url("https://fonts.googleapis.com/css?family=PT+Serif:400,700&subset=latin,cyrillic");article,aside,audio,b,body,canvas,dd,details,div,dl,dt,em,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,img,label,li,mark,menu,nav,ol,p,section,span,strong,summary,table,tbody,td,tfoot,th,thead,time,tr,u,ul,video{margin:0;padding:0;border:0;outline:0;vertical-align:baseline;background:0 0;font-size:100%}a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:0 0}table{border-collapse:collapse;border-spacing:0}td,td img{vertical-align:top}button,input,select,textarea{margin:0;font-size:100%}input[type=password],input[type=text],textarea{padding:0}input[type=checkbox]{vertical-align:bottom}input[type=radio]{vertical-align:text-bottom}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}html{overflow-y:scroll}body{color:#111;text-align:left;font:12px Verdana,"Geneva CY","DejaVu Sans",sans-serif}button,input,select,textarea{font-family:Verdana,"Geneva CY","DejaVu Sans",sans-serif}a,a:active,a:focus,a:hover,a:visited,button,input[type=button],input[type=submit],label{cursor:pointer}::selection{background:#84d5e8;color:#fff;text-shadow:none}html{position:relative;background:#f8f8f8 url("[[++assets_url]]components/upgrademodx/images/base.png")}body{background:0 0;font-size:14px;line-height:22px;font-family:"Helvetica Neue",helvetica,arial,sans-serif;text-shadow:0 1px 0 #fff}a{color:#0f7096}.button,button{color:#fff;display:inline-block;padding:15px;font-size:20px;text-decoration:none;border:5px solid #fff;border-radius:8px;background-color:#67a749;background-image:linear-gradient(to top,#67a749 0,#67a749 27.76%,#a1c755 100%);text-shadow:0 0 2px rgba(0,0,0,.64)}a.button{padding:5px 15px}h1,h2,h3,h4,h5{font-family:"PT Serif",helvetica,arial,sans-serif;line-height:28px}h1{font-size:26px}h2{font-size:22px}h3{font-size:18px}h4{font-size:16px}h5{font-size:14px}.header{-moz-box-sizing: border-box;float:left;width:100%;box-sizing:border-box;background:#fff;background:linear-gradient(to bottom,#fff,#f2f2f2);padding:20px;border-bottom:1px solid #fff}.header img{float:left;width:180px;margin:0 5px 0 0}.header h1.main-heading{color:#1778b6;font-size:32px;line-height:40px}.header-button-wrapper{float:right}.main-heading>span{display:none}.main-heading>sup{color:#ccc;font-weight:400}.content_div{float:left;padding:30px}.content_div h2{margin:0;line-height:20px}.content_div form{margin:10px 0 50px}.content.div form .column{float:left;box-sizing:border-box;width:500px;margin:20px 0}.column h3{display:inline-block;padding:0 0 5px;margin:0 0 20px;border-bottom:2px solid #000}.column label{float:left;width:100%;clear:both;padding:3px 0}form button{float:left;width:200px;clear:both}label>span{border-bottom:1px dotted #555}label>input{margin:0 5px 0 0}.footer{position:absolute;bottom:20px;right:20px;font-size:10px;color:#ccc}.footer a{color:#aaa} 
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
   
</head>
<body>
    <div class="header">
        <img src="https://modx.com/assets/i/logos/v5/svgs/modx-color.svg" alt="Logo">
        <h1 class="main-heading"><span>MODX</span> UpgradeMODX </h1>
    </div>

    <div class="content_div">
EOD;

if ($submitted) {
    $output .= <<<EOD
<script src="http://localhost/addons/assets/mycomponents/upgrademodx/_build/test/3d/js/modernizr.custom.js"></script>
EOD;

}

if (! $method) {
    MODXInstaller::quit("\n" . '<h2>Cannot download the files - neither cURL nor allow_url_fopen is enabled on this server.</h2>');
} else {
    if (!$submitted) {
        $output .= "\n<h2>Choose MODX version for Upgrade</h2>";
        $output .= "<br><h3> (Using  {$method})</h3>";
    }
}

$output .= MODXInstaller::createVersionForm($InstallData, $submitted);

/* This JS Polls the status file and updates the text if the content has changed */
if ($submitted) {
    $output .= <<<EOD
    <script>
        var previous = "Begin Upgrade";
        var url = "[[+ugm_progress_url]]";
             
        poll();            
          
        function poll() {
            var progress = document.getElementById('button_content');
            $.ajax({
            
                type: "GET",
                url: url,
                cache: false,
                data: {},
                dataType:"text",
                timeout: 2000,
               
                success: function(data){
                    if (data !== previous) {
                        
                        progress.innerHTML = data;
                       // console.log(progress);
                       // console.log("Data:" + data);
                        previous = data;
                        
                    }
                },
                complete: function(data) {
                    var s = data.responseText;
                    if (s.includes("Launching Setup") !== true) {
                        if (s.includes("+Finished") !== true) {
                            setTimeout(poll, 2000);
                        }
                    } else {
                        setTimeout(function () {
                            window.location.replace("http://localhost/addons/setup/index.php");
                        }, 4000);
                        
                    }
                },
                
                error : function (x, d, e) {
                  if (x.status === -5) {
                      /* ToDo: Make these dialogs */
                      alert("You are offline!! Please Check Your Network.");
                  } else {
                      if (x.status === 404) {
                         alert("Requested URL not found");
                      } else {
                          if (x.status === 500) {
                              alert("Internal Server Error.");
                          } else {
                              if (e === "parsererror") {
                                  alert("Error: Parsing JSON Request failed.");
                              } else {
                                  if (e === "timeout") {
                                      console.log("Request Time out.");
                                  } else {
                                      console.log("Unknown Error: " + x.responseText);
                                  }
                              }
                          }
                      }
                  }
                }
           });
        }
    </script>
EOD;
}

$output .= "\n    </div>"; // end content div

if (!$submitted) {
    $output .= <<<EOD

    <div class="footer">
        <p>Originally created by <a href="//ga-alex.com" title="">Bumkaka</a> & <a href="//dmi3yy.com" title="">Dmi3yy</a></p>
        <p>Modified for Revolution only by <a href="//sottwell.com" title="">sottwell</a></p>
        <p>Modified for Upgrade only with dashboard widget by <a href="//bobsguides.com" title="">BobRay</a></p>
        <p>Designed by <a href="//a-sharapov.com" title="">Sharapov</a></p>
    </div>
EOD;
} else {

    /* This JS manages starting and stopping the rotation and the progress bar */
    $output .= <<<EOD
    <script src="http://localhost/addons/assets/mycomponents/upgrademodx/_build/test/3d/js/classie.js"></script>
    <script src="http://localhost/addons/assets/mycomponents/upgrademodx/_build/test/3d/js/progressButton.js"></script>
    <script>
        
        var bttn = document.getElementById('ugm_submit_button');
        var old = '';
        new ProgressButton( bttn, {
                callback : function( instance ) {
                    
                    var progress = 0,
                        interval = setInterval( function() {
                            // console.log('Progress: ' + progress);
                            var button_text = document.getElementById('button_content').textContent ||
                                document.getElementById('button_content').innerText;
                            if (button_text == 'Downloading Files' && button_text != old) {
                                progress = 0.1;
                                old = button_text;
                            } else if (button_text == 'Unzipping Files' && button_text != old) {
                                progress = 0.3;
                                old = button_text;
                            } else if (button_text == 'Copying Files' && button_text != old) {
                                progress = 0.6;
                                old = button_text;
                            } else if (button_text == 'Preparing Setup' && button_text != old) {
                                progress = 0.8;
                                old = button_text;
                            }  else if (button_text == 'Finished') {
                                progress = 1;
                            }  else if (button_text == 'Launching Setup') {
                                progress = 1;
                            }
                            // progress = Math.min( progress + Math.random() * 0.1, 1 );
                            progress = Math.min( progress, 1 );
                            // console.log("Text " + button_text);
                            if( progress === 1 ) {
                                setTimeout(function () {
                                    instance._stop(1);
                                    clearInterval( interval );
                                }, 1000);
                            }
                            instance._setProgress( progress );
                            if( progress === 1 ) {
                                setTimeout(function () {
                                    instance._stop(1);
                                    clearInterval( interval );
                                }, 1000);
                            }
                        }, 1000 );
                }
            } );

        setTimeout(function () {
            bttn.click();
        }, 1000);
    </script>
EOD;

}


$output .= "\n</body>";
$output .= "\n</html>";

echo $output;
ob_flush();
flush();


/* Next two lines for running in debugger  */
// if (true || !empty($_GET['modx']) && is_scalar($_GET['modx']) && isset($InstallData[$_GET['modx']])) {
//       $rowInstall = $InstallData['revo2.4.1-pl'];
// Comment our the two lines below to run in debugger.

if ($submitted) {

    if (file_exists('config.core.php')) {
        @include 'config.core.php';
    }
    if (!defined('MODX_CORE_PATH')) {
        MODXInstaller::quit('Could not read config.core.php');
    }

    @include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';

    if (!defined('MODX_CONNECTORS_PATH')) {
        MODXInstaller::quit('Could not read main config file');
    }

    $devMode = false;
    $rowInstall = $InstallData[$_GET['modx']];

    /* Set true $devMode; DO NOT DELETE the next line*/
    /* [[+devMode]] */

    /* run unzip and install */

    /* Delete existing modx.zip file */
    $source = dirname(__FILE__) . "/modx.zip";
    $url = $rowInstall['link'];
    $certPath = MODX_CORE_PATH . 'components/upgrademodx/cacert.pem';
    if (!file_exists($certPath)) {
        MODXInstaller::quit('Could not find cacert.pem');
    }
    set_time_limit(0);

    /* Initialize progress file */
    $progressFilePath = '[[+ugm_progress_path]]';
    $success = MODXInstaller::updateProgress($progressFilePath, 'Starting Upgrade');
    sleep(2);

    if (!$success) {
        MODXInstaller::quit('Could not write to ugmprogress file: ' . $path);
    }

    MODXInstaller::updateProgress($progressFilePath, 'Downloading Files');

    /* Make sure we have the downloaded file */

    if (!$devMode) {
        $success = MODXInstaller::downloadFile($url, $source, $method, $certPath);
        if ($success !== true) {
            MODXInstaller::quit($success);
        } elseif (!file_exists($source)) {
            MODXInstaller::quit('Missing file: ' . $source);
        } elseif (filesize($source) < 64) {
            MODXInstaller::quit('File: ' . $source . ' is empty -- download failed');
        }
    } else {
        sleep(2);
    }

    /* Make temp directory */
    $tempDir = realPath(dirname(__FILE__)) . '/ugmtemp';
    MODXInstaller::mmkdir($tempDir);
    clearstatcache();

    $destination = $tempDir;

    if (!file_exists($tempDir)) {
        MODXInstaller::quit('Unable to create directory: ' . $tempDir);
    }

    if (!is_readable($tempDir)) {
        MODXInstaller::quit('Unable to read from /ugmtemp directory');
    }

    /* Unzip File */

    MODXInstaller::updateProgress($progressFilePath, 'Unzipping Files');
    set_time_limit(0);

    if ($devMode) {
        $success = true;
        sleep(2);
    } else {
        $success = MODXInstaller::unZip(MODX_CORE_PATH, $source, $destination, $forcePclZip);
    }
    if ($success !== true) {
        MODXInstaller::quit($success);
    }

    /* Get directories for file copy */
    $directories = MODXInstaller::getDirectories();
    $directories = MODXInstaller::normalize($directories);

    if (!$devMode) {
        $sourceDir = $tempDir . '/' . MODXInstaller::getModxDir($tempDir);
        $sourceDir = MODXInstaller::normalize($sourceDir);
    }

    /* Copy MODX files to destination */
    MODXInstaller::updateProgress($progressFilePath, 'Copying Files');
    if ($devMode) {
        sleep(2);
    } else {
        MODXInstaller::copyFiles($sourceDir, $directories);
        unlink($source);

        if (!is_dir(MODX_BASE_PATH . 'setup')) {
            MODXInstaller::quit('File Copy Failed');
        }

        MODXInstaller::removeFolder($tempDir, true);

        /* Clear cache files but not cache folder */

        $path = MODX_CORE_PATH . 'cache';
        if (is_dir($path)) {
            MODXInstaller::removeFolder($path, false);
        }

        unlink(basename(__FILE__));
    }

    /* Copy root config.core.php to setup/includes and add setup key */
    MODXInstaller::updateProgress($progressFilePath, 'Preparing Setup');
    if ($devMode) {
        sleep(2);
    } else {
        sleep(1);
        $rootCoreConfig = MODX_BASE_PATH . 'config.core.php';
        if (file_exists($rootCoreConfig)) {
            $newStr = "define('MODX_SETUP_KEY', '@traditional@');\n?>";
            $content = file_get_contents($rootCoreConfig);
            if (strpos($content, 'MODX_SETUP_KEY') === false) {
                if (strpos($content, '?>') !== false) {
                    $content = str_replace('?>', $newStr, $content);
                } else {
                    $content .= "\n" . $newStr;
                }
                file_put_contents(MODX_BASE_PATH . 'setup/includes/config.core.php', $content);
            }
        }
    }
}
    /* Instantiate MODX; Log upgrade in Manager Actions log; Launch setup */
if ($submitted) {
    if ($devMode) {
        MODXInstaller::updateProgress($progressFilePath, 'Finished');
    } else {
        include MODX_CORE_PATH . 'model/modx/modx.class.php';
        $modx = new modX();
        $modx->initialize('web');
        $modx->lexicon->load('core:default');
        $modx->logManagerAction('Upgrade MODX', 'modWorkspace', $modx->lexicon('version') . ' ' . $_GET['modx'], $_GET['userId']);
        /* Redirect done with 'replace' in JavaScript when it sees 'Launching MODX'. */
        // $modx->sendRedirect($rowInstall['location']);
        $modx = null;
        MODXInstaller::updateProgress($progressFilePath, 'Launching Setup');
    }
}

