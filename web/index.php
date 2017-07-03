<?php
require_once '../globals.php';
require_once '../includes/models/SensorsModel.php';
require_once '../includes/models/UsersModel.php';
chkLogin();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sensors</title>
        <style>
            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }
            .switch input {display:none;}
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }
            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }
            input:checked + .slider {
                background-color: #2196F3;
            }
            input:focus + .slider {
                box-shadow: 0 0 1px #2196F3;
            }
            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }
            /* Rounded sliders */
            .slider.round {
                border-radius: 34px;
            }
            .slider.round:before {
                border-radius: 50%;
            }
        </style>
        <script src="templates/js/jquery.min.js"></script>
    </head>
    <body>
        <?php
        $sensorob = new SensorsModel(); $rid = 0; $room_num = 0;
        while (TRUE) {
            $rid++;
            $room = $sensorob->getRoom($rid);
            if (!$room)
                break;
            echo '<h3>Room ' . ++$room_num . '</h3>';
            foreach ($room as $key => $value) {
                foreach ($value as $k => $v) {
                    if (strpos($k, 'sid')) {
                        $sen = $sensorob->getBySid($v);
                        if ($sen['sensorType'] == 'alarm') {
                            alarm_draw($value, $sen);
                            break;
                        }
                        echo '<div id="' . $v . '">' . $sen['name'];
                    }
                    if (strpos($k, 'tate') != NULL) {
                        echo 'Status<label class="switch">
                                <input type="checkbox" id="state" ';
                        if ($v)
                            echo'checked';echo'>
                                <div class="slider round"></div>
                            </label>';
                    }
                    if (strpos($k, 'axVa')) {
                        echo '<label for="val">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Max Value</label>
                                <input type="range" id="val" value="' . $v . '" min="1" max="100"><br><br>';
                    }
                    if (strpos($k, 'reVa')) {
                        echo '<label for="val">Prefered Value</label>
                                <input type="range" id="val" value="' . $v . '" min="15" max="40"><br><br>';
                    }
                    if (strpos($k, 'urVa')) {
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Current Value: ' . $v;
                        echo'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }
                echo '</div>';
            }
        }
        function alarm_draw($val, $sen) {
            echo '<div id="' . $val['sensors_sid'] . '">' . $sen['name'];
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Status: ';
            echo ($val['state'] == 1) ? 'on' : 'off';
        }
        ?>
        <script>
            $(function () {
                $("input").on('click', function () {
                    var parent = $(this).parents('div');
                    var id = parent.attr('id');
//                    var status = $this.data('status');
                    var status = parent.find('#state').is(':checked');
//                    var value = $this.parents('div').children('input').attr('value');
                    var value = parent.find('#val').val();
                    var data = {};
                    if (id) {
                        data.id = id;
                    }
                    if (parent.find('#state')) {
                        data.state = status;
                    }
                    if (value) {
                        data.val = value;
                    }
                    console.log(data);
                    $.ajax({
                        url: 'sensors.php',
                        type: 'post',
                        data: data
                    })
                            .done(function (data) {
                            })
                            .fail(function (data) {
                                alert("Error, reload page");
                                location.reload();
                            });
                });
            });
        </script>
    </body>
</html>