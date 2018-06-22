<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Progress Button Styles</title>

        <meta name="author" content="Bob Ray" />
        <style>
            article,aside,details,figcaption,figure,footer,header,hgroup,main,nav,section,summary{display:block;}audio,canvas,video{display:inline-block;}audio:not([controls]){display:none;height:0;}[hidden]{display:none;}html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;}body{margin:0;}a:focus{outline:thin dotted;}a:active,a:hover{outline:0;}h1{font-size:2em;margin:0.67em 0;}abbr[title]{border-bottom:1px dotted;}b,strong{font-weight:bold;}dfn{font-style:italic;}hr{-moz-box-sizing:content-box;box-sizing:content-box;height:0;}mark{background:#ff0;color:#000;}code,kbd,pre,samp{font-family:monospace,serif;font-size:1em;}pre{white-space:pre-wrap;}q{quotes:"\201C" "\201D" "\2018" "\2019";}small{font-size:80%;}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline;}sup{top:-0.5em;}sub{bottom:-0.25em;}img{border:0;}svg:not(:root){overflow:hidden;}figure{margin:0;}fieldset{border:1px solid #c0c0c0;margin:0 2px;padding:0.35em 0.625em 0.75em;}legend{border:0;padding:0;}button,input,select,textarea{font-family:inherit;font-size:100%;margin:0;}button,input{line-height:normal;}button,select{text-transform:none;}button,html input[type="button"],input[type="reset"],input[type="submit"]{cursor:pointer;}button[disabled],html input[disabled]{cursor:default;}input[type="checkbox"],input[type="radio"]{box-sizing:border-box;padding:0;}input[type="search"]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box;}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration{-webkit-appearance:none;}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0;}textarea{overflow:auto;vertical-align:top;}table{border-collapse:collapse;border-spacing:0;}
        </style>


        <link rel="stylesheet" type="text/css" href="http://localhost/addons/assets/mycomponents/upgrademodx/assets/components/upgrademodx/css/progress.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://localhost/addons/assets/mycomponents/upgrademodx/assets/components/upgrademodx/js/modernizr.custom.js"></script>
    </head>
    <body>
    <p class="header">
        <img src="https://modx.com/assets/i/logos/v5/svgs/modx-color.svg" alt="Logo">
        <h1 class="main-heading"><span>MODX</span> UpgradeMODX </h1>

    <div class="content_div">
    <!--<div class="container">-->

        <!--<div class="wrapper">-->
            <?php
            preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
            if (count($matches) < 2) {
                preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
            }

            if (count($matches) > 1): ?>
                <button id="ugm_submit_button" class="progress-button" data-style="fill"
                        data-horizontal>Begin Upgrade</button>
            <?php else: ?>
                <button id="ugm_submit_button" class="progress-button" data-style="rotate-angle-bottom" data-perspective
                        data-horizontal>Begin Upgrade</button>
            <?php endif; ?>
        <!--</div>-->

  <!--  </div>-->
        <script type = "text/javascript" src="http://localhost/addons/assets/mycomponents/upgrademodx/assets/components/upgrademodx/js/classie.js"></script>
        <script type="text/javascript" src="http://localhost/addons/assets/mycomponents/upgrademodx/assets/components/upgrademodx/js/progressButton.js"></script>
        <script>
            $('#ugm_submit_button').hover(
                function () {
                    if (! $(this).attr("disabled")) {
                        $(this).addClass('red');
                    } else {
                        $(this).removeClass('red');
                    }
                },
                function () {
                    $(this).removeClass('red');
                }
            );

            var bttn = document.getElementById('ugm_submit_button');

            new ProgressButton( bttn, {
                    callback : function( instance ) {
                        var progress = 0,
                            interval = setInterval( function() {
                                var content = document.getElementById('button_content');
                                progress = Math.min( progress + Math.random() * 0.1, 1 );
                                if( progress === 1 ) {
                                    instance._stop(1);
                                    clearInterval( interval );
                                }
                                if (progress > .1 && progress <=.3) {
                                    content.innerHTML = 'Downloading Files';
                                } else if (progress > .3 && progress <= .5) {
                                    content.innerHTML = 'Unzipping Files';
                                } else if (progress > .5 && progress <= .8) {
                                    content.innerHTML = 'Copying Files';
                                } else if (progress > .8 && progress <= .9) {
                                    content.innerHTML = 'Preparing Setup';
                                } else if (progress > .9 && progress <= .95) {
                                    content.innerHTML = 'Launching Setup';
                                }
                                instance._setProgress( progress );
                            }, 1000 );
                    }
                } );
            setTimeout(function () {
                bttn.click();
            }, 1000);
        </script>
    </div> <!-- end content_div div -->
    </body>
</html>