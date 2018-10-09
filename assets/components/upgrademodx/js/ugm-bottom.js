/**
 * JS file for UpgradeMODX extra
 *
 * Copyright 2018 by Bob Ray <https://bobsguides.com>
 * Created on 07-19-2018
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
 * @package upgradeMODX
 */

/* Set background color of selected version */
var checkedBackground = '#f0f0f0';
// var originalBackground = $('label').css("background-color");
var originalBackground = 'transparent';

$('input[type="radio"]:checked').parent().css("background", checkedBackground);

$("label > input").change(function () {
    if ($(this).is(":checked")) {
        $(this).parent().css("background", checkedBackground);
        $('input[type="radio"]:not(:checked)').parent().css("background", originalBackground);
        // console.log("Value: " + $('input[type="radio"]:checked').val());
    }
});

$(document).ajaxError(function (event, request, settings) {
    $(alert("Error requesting page " + settings.url));
});

var bttn = document.getElementById('ugm_submit_button');
var old = '';
new ProgressButton(bttn, {
    callback: function (instance) {

        //alert("Clicked");
        var button_text = document.getElementById('button_content');
        var progress = 0;
        var selectedVersion = 'modx-' + $("input:radio[name='modx']:checked").val() + '.zip';
        var updateText = function (button_text, msg) {
            if ('textContent' in button_text) {
                button_text.textContent = msg;
            } else {
                button_text.innerText = msg;
            }
        };

        var process = function () {
            if (progress === 0) {
                // console.log("progress is zero");
                updateText(button_text, 'Downloading Files');
                progress = 0.1;
                instance._setProgress(progress);
                var maxProgress = 0.3;
                var progressInterval = setInterval(function () {
                    if (progress < 1.0 && progress < maxProgress) {
                        progress += 0.01;
                        instance._setProgress(progress);
                        if (progress >= 1) {
                            clearInterval(progressInterval);
                        }
                    }


                }, 1000);

                $.ajax({
                    cache: false,
                    type: 'GET',
                    // url: 'http://localhost/addons/assets/mycomponents/upgrademodx/assets/components/upgrademodx/connector.php',
                    url: ugmConnectorUrl,
                    data: {
                        'action': 'downloadfiles',
                        'props': ugm_config,
                        'version': selectedVersion
                    },
                    success: function (data) {
                        if (data.success === true) {
                            updateText(button_text, data.message);
                            // alert("Got success return from downloadfiles");
                            progress = 0.3;
                            maxProgress = 0.6;
                            instance._setProgress(progress);

                            /* Run next processor */
                            //  console.log(ugm_config);
                            $.ajax({
                                type: 'GET',
                                url: ugmConnectorUrl,
                                data: {
                                    'action': 'unzipfiles',
                                    'props': ugm_config,
                                    'version': selectedVersion
                                },
                                success: function (data) {
                                    if (data.success === true) {
                                        updateText(button_text, data.message);
                                        // alert("Got success return from unzipfiles");
                                        progress = 0.6;
                                        maxProgress = 0.8;
                                        instance._setProgress(progress);
                                        /* Run next processor */
                                        $.ajax({
                                            type: 'GET',
                                            url: ugmConnectorUrl,
                                            data: {
                                                'action': 'copyfiles',
                                                'props': ugm_config,
                                                'version': selectedVersion
                                            },
                                            success: function (data) {
                                                if (data.success === true) {
                                                    updateText(button_text, data.message);
                                                    // alert("Got success return from copyfiles");
                                                    progress = 0.8;
                                                    maxProgress = 0.85;
                                                    instance._setProgress(progress);
                                                    /* Run next processor */
                                                    $.ajax({
                                                        type: 'GET',
                                                        url: ugmConnectorUrl,
                                                        data: {
                                                            'action': 'preparesetup',
                                                            'props': ugm_config,
                                                            'version': selectedVersion
                                                        },
                                                        success: function (data) {
                                                            if (data.success === true) {
                                                                updateText(button_text, data.message);
                                                                // alert("Got success return from copyfiles");
                                                                progress = 0.85;
                                                                maxProgress = 0.98;
                                                                instance._setProgress(progress);
                                                                /* Run next processor */
                                                                $.ajax({
                                                                    type: 'GET',
                                                                    url: ugmConnectorUrl,
                                                                    data: {
                                                                        'action': 'cleanup',
                                                                        'props': ugm_config,
                                                                        'version': selectedVersion
                                                                    },
                                                                    success: function (data) {
                                                                        if (data.success === true) {
                                                                            updateText(button_text, data.message);
                                                                            //  alert("Got success return from preparesetup");
                                                                            progress = 1;
                                                                            instance._setProgress(progress);
                                                                            instance._stop(1);
                                                                        } else {
                                                                            displayError(data.message, progressInterval, instance);
                                                                        }
                                                                        clearInterval(progressInterval);
                                                                        //console.log(ugm_setup_url);
                                                                        setTimeout(function () {
                                                                            window.location.replace(ugm_setup_url);
                                                                        }, 1500);

                                                                    },
                                                                    dataType: 'json'
                                                                });
                                                            } else {
                                                                displayError(data.message, progressInterval, instance);
                                                            }
                                                            // clearInterval(progressInterval);
                                                            // window.location.replace(ugm_setup_url);
                                                        },
                                                        dataType: 'json'
                                                    });

                                                } else {
                                                    displayError(data.message, progressInterval, instance);
                                                }
                                                // console.log(data.message);
                                            },
                                            dataType: 'json'
                                        });

                                    } else {

                                        displayError(data.message, progressInterval, instance);
                                    }
                                    //console.log(data.message);
                                },
                                dataType: 'json'
                            });


                        } else {
                            displayError(data.message, progressInterval, instance);
                            console.log(data.message);
                        }
                    },
                    dataType: 'json'
                });
            }
            progress = Math.min(progress, 1);

            if (progress === 1) {
                setTimeout(function () {
                    instance._stop(1);
                    clearInterval(interval);
                }, 1000);
            }
            instance._setProgress(progress);
            if (progress === 1) {
                setTimeout(function () {
                    instance._stop(1);
                    // clearInterval(interval);
                }, 500);
            }
        };
        process();
    }
});

function displayError($msg, progressInterval, instance) {
    clearInterval(progressInterval);
    instance._stop(1);
    $("#ugm_submit_button").fadeOut(400, function () {
        $(this).html($msg).fadeIn();
    });

    // bttn.innerHTML = $msg;
}
