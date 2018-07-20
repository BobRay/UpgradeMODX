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
var checkedBackground = '#ffffff';
var originalBackground = $('label').css("background-color");

$('input[type="radio"]:checked').parent().css("background", checkedBackground);

$("label > input").change(function () {
    if ($(this).is(":checked")) {
        $(this).parent().css("background", checkedBackground);
        $('input[type="radio"]:not(:checked)').parent().css("background", originalBackground);
        console.log("Value: " + $('input[type="radio"]:checked').val());
    }
});

var bttn = document.getElementById('ugm_submit_button');
var old = '';
new ProgressButton(bttn, {
    callback: function (instance) {

        var progress = 0,
            interval = setInterval(function () {
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
                } else if (button_text == '[[+ugm_finished]]') {
                    progress = 1;
                } else if (button_text == '[[+ugm_launching_setup]]') {
                    progress = 1;
                }
                // progress = Math.min( progress + Math.random() * 0.1, 1 );
                progress = Math.min(progress, 1);
                // console.log("Text " + button_text);
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
                        clearInterval(interval);
                    }, 1000);
                }
            }, 1000);
    }
});


