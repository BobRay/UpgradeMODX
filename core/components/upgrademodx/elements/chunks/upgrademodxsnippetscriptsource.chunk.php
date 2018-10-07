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
                $open = $zip->open($source, ZipArchive::CHECKCONS);

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
            MODXInstaller::quit ('[[+ugm_cannot_read_directory]]: ' . dirname(__FILE__) . '/temp');
        }

        if (empty($dir)) {
            MODXInstaller::quit('[[+ugm_unknown_error_reading_temp]]');
        }

        return $dir;
    }

    public static function updateProgress($path, $content) {
        return file_put_contents($path, $content, LOCK_EX);
    }

    /** Returns IE version number or false if not using ID
     *  Note: returns false for Edge
     */
    public static function getIeVersion() {
        $version = false;
        preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
        if (count($matches) < 2) {
            preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
        }

        if (count($matches) > 1) {
            // We're using IE < version 12 (Edge)
            $version = $matches[1];
        }

        return $version;
    }
    public static function getButtonCode($action = "[[+ugm_begin_upgrade]]", $disabled = false, $submitted = false) {
        $disabled = $disabled? ' disabled ' : '';
        $red = $submitted? ' red': '';
        $ie = MODXInstaller::getIeVersion();

        if ($ie) {
            $buttonCode =  '
        <button type="submit" name="IeSucks" id="ugm_submit_button" class="progress-button' . $red . '" data-style="fill"
                data-horizontal' . $disabled . '><span id="button_content" class="content">' . $action . '</span></button>';
        } else {
            $buttonCode =  '
        <button type = "submit" name="IeSucks" id="ugm_submit_button" class="progress-button' . $red . '" data-style="rotate-angle-bottom" data-perspective
                data-horizontal' . $disabled . '><span id="button_content" class="content">' . $action . '</span></button>';
        }

        return $buttonCode;
    }
    public static function createVersionForm($InstallData, $submitted) {
        $output = '';
        if (! $submitted) { // no form tags on landing page after submission so form won't submit again
            $output .= "\n" . '<form action="" name="upgrade_form" id="upgrade_form" method="post">' . "\n";
        } else {
            $output .= "\n" . '<div id="upgrade_form">' . "\n";
        }

        $action = $submitted? "[[+ugm_starting_upgrade]]" : "[[+ugm_begin_upgrade]]";
        $disabled = $submitted ? true : false;
        $output .= MODXInstaller::getButtonCode($action, $disabled, $submitted);

        /* If not submitted, add version list */

        if (! $submitted) {
            $ItemGrid = array();
            foreach ($InstallData as $ver => $item) {
                $ItemGrid[$item['tree']][$ver] = $item;
            }
            $output .= "<p>[[+ugm_get_major_versions]]</p>";
            //  $i = 0;
            foreach ($ItemGrid as $tree => $item) {
                $output .= "\n" . '<div class="column">';
                   /* "\n<h3>" . strtoupper($tree) . '</h3>';*/
                foreach ($item as $version => $itemInfo) {
                    $selected = $itemInfo['selected'] ? ' checked' : '';
                    $current = $itemInfo['current'] ? ' &nbsp;&nbsp;([[+ugm_current_version_indicator]])' : '';

                    $output .= <<<EOD
        <label><input type="radio"{$selected} name="modx" value="$version">
            <span>{$itemInfo['name']} $current</span>
        </label>
EOD;
                } // end inner foreach loop
                $output .= '</div>';
            } // end outer foreach loop
            $output .= "\n    " . '<input type="hidden" name="userId" value="[[+modx.user.id]]">';
        }
        if (!$submitted) { // no form tags on landing page so form won't submit again
            $output .= "\n" . '</form>' . "\n ";
        } else {
            $output .= "\n" . '</div>' . "\n ";
        }
        return $output;
    }

    public static function process() {
        /* Do not touch the following comments! You have been warned!  */
        /** @var $forcePclZip bool - force the use of PclZip instead of ZipArchive */
        /* [[+ForcePclZip]] */
        /* [[+InstallData]] */
        /** @var $InstallData array */

        $method = 0;
        $output = '';
        $submitted = !empty($_POST['modx']) && is_scalar($_POST['modx']) && isset($InstallData[$_POST['modx']]);

        if ($submitted) {
            /* validate MODX Version and user ID */
            $pattern = '/^\d{1,3}\.\d{1,3}\.\d{1,3}-(pl|rc|alpha|beta)\d{0,3}$/';
            if (!preg_match($pattern, (string)$_POST['modx'])) {
                die('Invalid Version');
            }
            if (!isset($_POST['userId'])) {
                die ("no user ID");
            }
            if (preg_match('/[^0-9]/', (string)$_POST['userId'])) {
                die ('Invalid User ID');
            }

        }

        $output .= <<<EOD
<!DOCTYPE html>
<html lang="[[+ugm_manager_language]]">
<head>

    <title>UpgradeMODX</title>
    <meta charset="utf-8">
    
    
    <link rel="apple-touch-icon" sizes="57x57" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="[[+ugm_assets_url]]css/favicons.ico/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="[[+ugm_assets_url]]css/favicons.ico/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="[[+ugm_assets_url]]css/favicons.ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="[[+ugm_assets_url]]css/favicons.ico/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="[[+ugm_assets_url]]css/favicons.ico/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <link rel="stylesheet" href="[[+ugm_assets_url]]css/progress.css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/plugins/CSSPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/easing/EasePack.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/TweenLite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/plugins/ScrollToPlugin.min.js"></script>
    
   
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
<script src="[[+ugm_assets_url]]js/modernizr.custom.js"></script>
EOD;

        }

        if (!$method) {
            MODXInstaller::quit("\n" . '<h2>[[+ugm_no_method_enabled]]</h2>>');
        } else {
            if ($submitted) {
                $output .= "\n<h2>[[+ugm_updating_modx_files]]</h2>";
            } else {
                $output .= "\n<h2>[[+ugm_choose_version]]</h2>";
            }
            $output .= "<h3> ([[+ugm_using]] {$method})</h3>";
        }

        $output .= MODXInstaller::createVersionForm($InstallData, $submitted);

        /* This JS Polls the status file and updates the text if the content has changed */
        if ($submitted) {
            $output .= <<<EOD
    <script>
        var previous = "[[+ugm_begin_upgrade]]";
        var url = "[[+ugm_progress_url]]";
        
        /* Polyfill for IE */
        if (!String.prototype.includes) {
             String.prototype.includes = function(search, start) {
                if (typeof start !== 'number') {
                    start = 0;
                }

                if (start + search.length > this.length) {
                    return false;
                } else {
                    return this.indexOf(search, start) !== -1;
                }
          };
        }
             
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
                    if (s.includes("[[+ugm_launching_setup]]") !== true) {
                        if (s.includes("[[+ugm_finished]]") !== true) {
                            setTimeout(poll, 2000);
                        }
                    } else {
                        setTimeout(function () {
                            window.location.replace("[[++base_url]]setup/index.php");
                        }, 4000);
                        
                    }
                },
                
                error : function (x, d, e) {
                  if (x.status === -5) {
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
        } else {
            $output .= <<<EOD
        <script>
           "use strict";
            var subButton = $('#ugm_submit_button'); 
            var finished = false;
            var permanentRed = false;
            var checkedBackground = '#ffffff';
            var originalBackground = $('label').css( "background-color" );

            $('input[type="radio"]:checked').parent().css("background",checkedBackground);
             
            $("label > input").change(function() {
                if ($(this).is(":checked")) {
                    $(this).parent().css("background", checkedBackground);
                    $('input[type="radio"]:not(:checked)').parent().css("background",originalBackground); 
                } 
            });
            
            function forward() {
                    $('#upgrade_form').submit();
            }
              
            subButton.on("click", (function(e){
                e.preventDefault();
                permanentRed = true;
                $(this).addClass('red');
                // JQuery's janky scroll:
                // $('html,body').animate({scrollTop: 0}, 1000, "linear", forward);
               
               var top  = window.pageYOffset || document.documentElement.scrollTop;
               if (top === 0) {
                   forward();
               } else {
                    var x = new TweenLite.to(window, 1, {scrollTo:{y:0, x:0},ease:Power2.easeInOut});
                    x.eventCallback("onComplete", forward);
               }
            })); 

            subButton.hover(
                function () {
                    $(this).addClass('red');
                },
                function () {
                    if (! permanentRed) {
                    $(this).removeClass('red');
                    }
                }
            );
        </script>
EOD;
        }

        $output .= "\n    </div>"; // end content div

        if (!$submitted) {
            $output .= <<<EOD

    <div class="footer">
        <p>[[+ugm_originally_created_by]] <a href="//ga-alex.com" title="">Bumkaka</a> & <a href="//dmi3yy.com" title="">Dmi3yy</a></p>
        <p>[[+ugm_modified_for_revolution_by]] <a href="//sottwell.com" title="">sottwell</a></p>
        <p>[[+ugm_modified_for_upgrade_by]] <a href="//bobsguides.com" title="">BobRay</a></p>
        <p>[[+ugm_original_design_by]] <a href="//a-sharapov.com" title="">Sharapov</a></p>
    </div>
EOD;


            /* Adds text for debugging scroll */
            /*for ($i = 1; $i < 50; $i++) {
                $output .= "<br><p>Some Text</p>";
            }*/


        } else {

            /* This JS manages starting and stopping the rotation and the progress bar */
            $output .= <<<EOD
    <script src="[[+ugm_assets_url]]js/classie.js"></script>
    <script src="[[+ugm_assets_url]]js/progressButton.js"></script>
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
                            if (button_text == '[[+ugm_downloading_files]]' && button_text != old) {
                                progress = 0.1;
                                old = button_text;
                            } else if (button_text == '[[+ugm_unzipping_files]]' && button_text != old) {
                                progress = 0.3;
                                old = button_text;
                            } else if (button_text == '[[+ugm_copying_files]]' && button_text != old) {
                                progress = 0.6;
                                old = button_text;
                            } else if (button_text == '[[+ugm_preparing_setup]]' && button_text != old) {
                                progress = 0.8;
                                old = button_text;
                            }  else if (button_text == '[[+ugm_finished]]') {
                                progress = 1;
                            }  else if (button_text == '[[+ugm_launching_setup]]') {
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
        
        /* Simulate click on landing page to initiate action; button won't submit
           because it's not in a form 
         */
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
// if (true || !empty($_POST['modx']) && is_scalar($_POST['modx']) && isset($InstallData[$_POST['modx']])) {
//       $rowInstall = $InstallData['revo2.4.1-pl'];
// Comment our the two lines below to run in debugger.

        if ($submitted) {

            if (file_exists('config.core.php')) {
                @include 'config.core.php';
            }
            if (!defined('MODX_CORE_PATH')) {
                MODXInstaller::quit('[[+ugm_cannot_read_config_core_php]]');
            }

            @include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';

            if (!defined('MODX_CONNECTORS_PATH')) {
                MODXInstaller::quit('[[+ugm_cannot_read_main_config]]');
            }

            $devMode = false;
            $rowInstall = $InstallData[$_POST['modx']];

            /* Set true $devMode; DO NOT DELETE the next line*/
            /* [[+devMode]] */

            /* run unzip and install */

            /* Delete existing modx.zip file */
            $source = dirname(__FILE__) . "/modx.zip";
            $url = $rowInstall['link'];
            $certPath = MODX_CORE_PATH . 'components/upgrademodx/cacert.pem';
            if (!file_exists($certPath)) {
                MODXInstaller::quit('[[+could_not_find_cacert]]');
            }
            set_time_limit(0);

            /* Initialize progress file */
            $progressFilePath = '[[+ugm_progress_path]]';
            $success = MODXInstaller::updateProgress($progressFilePath, '[[+ugm_starting_upgrade]]');
            /* Necessary for first message to be read */
            sleep(2);

            if (!$success) {
                MODXInstaller::quit('[[+ugm_could_not_write_progress]]: ' . $progressFilePath);
            }

            MODXInstaller::updateProgress($progressFilePath, '[[+ugm_downloading_files]]');

            /* Make sure we have the downloaded file */

            if (!$devMode) {
                $success = MODXInstaller::downloadFile($url, $source, $method, $certPath);
                if ($success !== true) {
                    MODXInstaller::quit($success);
                } elseif (!file_exists($source)) {
                    MODXInstaller::quit('Missing file: ' . $source);
                } elseif (filesize($source) < 64) {
                    MODXInstaller::quit('[[+ugm_file]]: ' . $source . ' ' . '[[+ugm_is_empty_download_failed]]');
                }
            } else {
               sleep(2);
            }

            /* Make temp directory */
            $tempDir = realpath(dirname(__FILE__)) . '/ugmtemp';
            MODXInstaller::mmkDir($tempDir);
            clearstatcache();

            $destination = $tempDir;

            if (!file_exists($tempDir)) {
                MODXInstaller::quit('[[+ugm_unable_to_create_directory]]: ' . $tempDir);
            }

            if (!is_readable($tempDir)) {
                MODXInstaller::quit('[[+ugm_unable_to_read_ugmtemp]]');
            }

            /* Unzip File */

            MODXInstaller::updateProgress($progressFilePath, '[[+ugm_unzipping_files]]');
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
            MODXInstaller::updateProgress($progressFilePath, '[[+ugm_copying_files]]');
            if ($devMode) {
                sleep(2);
            } else {
                MODXInstaller::copyFiles($sourceDir, $directories);
                unlink($source);

                if (!is_dir(MODX_BASE_PATH . 'setup')) {
                    MODXInstaller::quit('[[+ugm_file_copy_failed]]');
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
            MODXInstaller::updateProgress($progressFilePath, '[[+ugm_preparing_setup]]');
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
                MODXInstaller::updateProgress($progressFilePath, '[[+ugm_finished]]');
            } else {
                include MODX_CORE_PATH . 'model/modx/modx.class.php';
                $modx = new modX();
                $modx->initialize('web');
                $modx->lexicon->load('core:default');
                $modx->logManagerAction('Upgrade MODX', 'modWorkspace', $modx->lexicon('version') . ' ' . $_POST['modx'], $_POST['userId']);
                /* Redirect done with 'replace' in JavaScript when it sees 'Launching Setup'. */
                // $modx->sendRedirect($rowInstall['location']);
                $modx = null;
                MODXInstaller::updateProgress($progressFilePath, '[[+ugm_launching_setup]]');
            }
        }

    }


    public static function quit($msg) {
        $begin = '<div style="margin:auto;margin-top:100px;width:40%;height:auto;padding:30px 30px 0;color:red;border:3px solid darkgray;text-align:center;background-color:rgba(160, 233, 174, 0.42);border-radius:15px;box-shadow: 10px 10px 5px #888888;"><p style="font-size: 14pt;">';
        $end = '</p><p style="margin-bottom:120px;"><a href="[[+manager_url]]">Back to Manager</a></p></div>';
        die($begin . $msg  . $end);
    }
} /* End of MODXInstaller class */

MODXInstaller::process();