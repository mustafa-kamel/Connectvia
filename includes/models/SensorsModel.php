<?php

class SensorsModel {

    /**
     * Get all rows from parent `sensors` table [array of rows]
     * @param string $extra
     * @return array2D
     */
    public function get($extra = '') {
        $sensors = array();
        System::get('db')->Execute("SELECT * FROM `sensors` $extra");
        if (System::get('db')->AffectedRows() > 0)
            $sensors = System::get('db')->GetRows();
        return $sensors;
    }

    /**
     * Get one sensor [single row] data from `sensors` table
     * @param integer $sid
     * @return array
     */
    public function getBySid($sid) {
        $id = (int) $sid;
        if ($sensor = $this->get("WHERE `sid`= $sid"))
            return $sensor[0];
    }

    /**
     * Get all sensors data in specific room from `sensors` table
     * @param integer $rid
     * @return array2D
     */
    public function getByRid($rid) {
        $rid = (int) $rid;
        $sensors = $this->get("WHERE `rid`= $rid");
        return $sensors;
    }

    /**
     * Get all sensors data in specific floor from `sensors` table
     * @param integer $fid
     * @return array2D
     */
    public function getByFid($fid) {
        $fid = (int) $fid;
        $sensors = $this->get("WHERE `fid`= $fid");
        return $sensors;
    }

    /**
     * Get state (and value if exists) of one sensor [one row] from children tables
     * useful to get state and values of sensors using its sid&tableName[sensorType] after getting data of the sensor from `sensors` table 
     * @param integer $sid
     * @return array
     */
    public function getSensor($sid) {
        $sid = (int) $sid;
        $sensor = $this->getBySid($sid);
        $table = $sensor['sensorType'];
        System::get('db')->Execute("SELECT * FROM `$table` WHERE `sensors_sid`= $sid");
        if (System::get('db')->AffectedRows() > 0)
            $sensor = System::get('db')->GetRows();
        return $sensor[0];
    }

    /**
     * Get states (and values if exist) of sensors in specific room [array of rows] from children tables
     * useful to get states and values of sensors using its sid after getting data of the sensors from `sensors` table 
     * @param integer $rid
     * @return array
     */
    public function getRoom($rid) {
        $rid = (int) $rid;
        $sensors = $this->getByRid($rid);
        $data = array();
        for ($i = 0; $i < count($sensors); $i++) {
            $sid = $sensors[$i]['sid'];
            $row = $this->getSensor($sid);
            array_push($data, $row);
        }
        return $data;
    }

    /**
     * Get states (and values if exist) of sensors in specific floor [array of rows] from children tables
     * useful to get states and values of sensors using its sid after getting data of the sensors from `sensors` table 
     * @param integer $fid
     * @return array
     */
    public function getFloor($fid) {
        $fid = (int) $fid;
        $sensors = $this->getByFid($fid);
        $data = array();
        for ($i = 0; $i < count($sensors); $i++) {
            $sid = $sensors[$i]['sid'];
            $row = $this->getSensor($sid);
            array_push($data, $row);
        }
        return $data;
    }

    /**
     * Get states (and values if exist) of sensors of specific type [array of rows] from children tables
     * useful to get states and values of sensors of specific type at once
     * @param string $sensorType
     * @return array
     */
    public function getCat($sensorType) {
        trim($sensorType);
        if ($sensorType != 'light' && $sensorType != 'temp' && $sensorType != 'appliances' && $sensorType != 'alarm' && $sensorType != 'log')
            return FALSE;
        $sensors= array();
        if ($sensorType != 'users') {
            System::get('db')->Execute("SELECT * FROM `$sensorType`");
            if (System::get('db')->AffectedRows() > 0)
                $sensors = System::get('db')->GetRows();
        }
        return $sensors;
    }
    
    /**
     * Get the contents of the `upateq` table so as to be sent to the RPi, then delete the them from the table
     * @return array
     */
    public function getUpdateq(){
        $commands= array();
        System::get('db')->Execute("SELECT * FROM `updateq`");
        if (System::get('db')->AffectedRows() > 0)
            $commands= System::get('db')->GetRows();
        for ($i = 0; $i < count($commands); $i++) {
            $sid = $commands[$i]['sid'];
            System::get('db')->Delete('updateq',"WHERE `sid`= $sid");
        }
        return $commands;
    }
    
    /**
     * Updates the status (and the val {`light`.`maxVal` or `temp`.`preVal`} if exists) of a sensor except [`temp`.`curVal`]
     * @param array $data: $data['sid', 'state'] or $data['sid', 'state', 'val']
     * @return boolean
     */
    public function updateSensor($data) {
        if (isset($data['sid'])){
            $sid= intval($data['sid']);
            $sensor= $this->getBySid($sid);
            $table= $sensor['sensorType'];
            $state= '';
            switch ($table) {
                case 'light':
                    $state= $data['state'];
                    $val= '';
                    $sdata= array('state'=> $state);
                    if (isset($data['val'])) {
                        $val = intval($data['val']);
                        $sdata['maxVal'] = $val;
                    }
                    if (System::get('db')->Update('light', $sdata, "WHERE `sensors_sid`= $sid"))
                        return TRUE;
                    break;
                
                case 'alarm':
                    $state= $data['state'];
                    if (System::get('db')->Update('alarm', array('state'=> $state), "WHERE `sensors_sid`= $sid"))
                        return TRUE;
                    break;
                
                case 'temp':
                    $state= $data['state'];
                    $val= '';
                    $sdata= array('state'=> $state);
                    if (isset($data['val'])) {
                        $val = intval($data['val']);
                        $sdata['preVal'] = $val;
                    }
                    if (System::get('db')->Update('temp', $sdata, "WHERE `sensors_sid`= $sid"))
                        return TRUE;
                    break;
                
                case 'appliances':
                    $state= $data['state'];
                    if (System::get('db')->Update('appliances', array('state'=> $state), "WHERE `sensors_sid`= $sid"))
                        return TRUE;
                    break;

                default:
                    return FALSE;
            }
        }
        return FALSE;
    }
    
    /**
     * Update the `curVal`(s) for the sensors in the `temp` table
     * Only used by RPi
     * @param array $data array ('sid' => $int, 'val'=> $int)
     * @return boolean
     */
    public function piTempUpdate($data){
        if (!empty($data)){
                $sid= $data['sid'];
                if (isset($data['val'])) {
                    $val = intval($data['val']);
                    $sdata['curVal'] = $val;
                }
                if (!System::get('db')->Update('temp', $sdata, "WHERE `sensors_sid`= $sid"))
                    return FALSE;
            return TRUE;
        }
        return FALSE;
    }

}
