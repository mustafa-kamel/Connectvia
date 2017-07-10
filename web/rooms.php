<?php
require_once '../globals.php';
require_once '../includes/models/SensorsModel.php';
require_once '../includes/models/UsersModel.php';
define("TITLE", "Rooms Control");
chkLogin();
?>
<?php include('header.php');?>        
<?php include('sidebar.php');?>

<?php
        $sensorob = new SensorsModel(); $rid = 0; $room_num = 0;
        $c = 0;
        echo '<div class="container" style="width: 96%; margin: 50px 300px 70px 50px;"><div class="row">';
        while (TRUE) {
            $rid++;
            $room = $sensorob->getRoom($rid);
            if (!$room) break;
            echo '<div class="col-md-4 col-xs-4 col-lg-4">';
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
                        echo 'Status<label class="switch"><input type="checkbox" id="state" ';
                        if ($v) echo'checked';
                        echo'><div class="slider round"></div></label>';
                    }
                    if (strpos($k, 'axVa')) {
                        echo '<form name="lightForm">
                                   <input type="range" name="lightInputValue" id="val" value="'.$v.'" min="1" max="100" oninput="lightOutputId.value = val.value">
                                   <p align="right"><output name="lightOutputValue" id="lightOutputId">'.$v.'</output></p>
                                </form>';
                    }
                    if (strpos($k, 'reVa')) {
                        
                        echo '<form name="temperatureForm">
                                   <input type="range" name="tempInputValue" id="val" value="'.$v.'" min="15" max="40" oninput="tempOutputId.value = val.value">
                                   <p align="right"><output name="tempOutputValue" id="tempOutputId">'.$v.'</output></p>
                                </form>';
                    }
                    if (strpos($k, 'urVa')) {
                        echo '<br />Current Value: '.$v;
                        echo'<br />';
                    }
                }
                echo '</div>';
            }
            echo '</div>';
            if ($c++ >= 2){
                $c=0;
                echo '</div><div class="row">';
            }
        }
        echo '</div>';
        function alarm_draw($val, $sen) {
            echo '<div id="' . $val['sensors_sid'] . '">' . $sen['name'];
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Status: ';
            echo ($val['state'] == 1) ? '<span class="redCircle">DANGER</span>' : '<span class="greenCircle">SAFE</span>';
        }
        ?>
        <script>
            $(function () {
                $("input").on('click', function () {
					//var parent = $(this).parents('div');
                    var parent = $(this).closest('div');
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
								console.log(data);
                            })
                            .fail(function (data) {
                                alert("Error, reload page");
                                location.reload();
                            });
                });
            });
        </script>        
                        
<?php include('footer.php');?>