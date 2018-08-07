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
        console.log("Value: " + $('input[type="radio"]:checked').val());
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
        var checked = $("input:radio[name='modx']:checked").val();

        var updateText = function (button_text, msg) {
            if ('textContent' in button_text) {
                button_text.textContent = msg;
            } else {
                button_text.innerText = msg;
            }
        };
        var  process = function () {
            if (progress === 0) {
                // console.log("progress is zero");
                updateText(button_text, 'Downloading Files');
                progress = 0.1;
                instance._setProgress(progress);

                $.ajax({
                    type: 'GET',
                    // url: 'http://localhost/addons/assets/mycomponents/upgrademodx/assets/components/upgrademodx/connector.php',
                    url: ugmConnectorUrl,
                    data: {
                        'action': 'downloadfiles',
                        'props': ugm_config,
                        'version' : checked
                    },
                    success: function (data) {
                        if (data.success === true) {
                            updateText(button_text, data.message);
                            // alert("Got success return from downloadfiles");
                            progress = 0.3;
                            instance._setProgress(progress);

                            /* Run next processor */
                           //  console.log(ugm_config);
                            $.ajax({
                                type: 'GET',
                                url: ugmConnectorUrl,
                                data: {
                                    'action': 'unzipfiles',
                                    'props': ugm_config
                                },
                                success: function (data) {
                                    if (data.success === true) {
                                        updateText(button_text, data.message);
                                       // alert("Got success return from unzipfiles");
                                        progress = 0.6;
                                        instance._setProgress(progress);
                                        /* Run next processor */
                                        $.ajax({
                                            type: 'GET',
                                            url: ugmConnectorUrl,
                                            data: {
                                                'action': 'copyfiles',
                                                'props': ugm_config
                                            },
                                            success: function (data) {
                                                if (data.success === true) {
                                                    updateText(button_text, data.message);
                                                   // alert("Got success return from copyfiles");
                                                    progress = 0.9;
                                                    instance._setProgress(progress);
                                                    /* Run next processor */
                                                    $.ajax({
                                                        type: 'GET',
                                                        url: ugmConnectorUrl,
                                                        data: {
                                                            'action': 'preparesetup',
                                                            'props': ugm_config
                                                        },
                                                        success: function (data) {
                                                            if (data.success === true) {
                                                                updateText(button_text, data.message);
                                                              //  alert("Got success return from preparesetup");
                                                               // progress = 1; // ToDo: restore this
                                                                instance._setProgress(progress);
                                                               // instance._stop(1); // ToDo: restore this
                                                                /* Run next processor */

                                                            } else {
                                                                displayError('data.message');
                                                            }
                                                            // console.log(data.message);
                                                        },
                                                        dataType: 'json'
                                                    });

                                                } else {
                                                    displayError('data.message');
                                                }
                                                // console.log(data.message);
                                            },
                                            dataType: 'json'
                                        });

                                    } else {
                                        displayError('data.message');
                                    }
                                    //console.log(data.message);
                                },
                                dataType: 'json'
                            });


                        } else {
                            displayError(data.message);
                            console.log(data.message);
                        }
                        // console.log(data.message);
                    },
                    dataType: 'json'
                });


            }

                /*else if(button_text == '[[+ugm_downloading_files]]' && button_text != old) {
                    // progress = 0.1;
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
                } else if (button_text == '[[+ugm_finished]]') {
                    progress = 1;
                } else if (button_text == '[[+ugm_launching_setup]]') {
                    progress = 1;
                }*/
                // progress = Math.min( progress + Math.random() * 0.1, 1 );
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
                    }, 1000);
                }
            };
        process();
    }
});

function displayError($msg) {
    $("#ugm_submit_button").fadeOut(400, function () {
        $(this).html($msg).fadeIn();
    });

    // bttn.innerHTML = $msg;
}


