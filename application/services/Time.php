<?php
class Service_Time extends Aw_Service_ServiceAbstract {
    
    private $_time = null;
    
    private $_start, $_end = null;
    
    public function setTime($time) {
        $this->_start = $this->_end = null;
        $this->_time = $time;
    }
    
    public function _expand() {
        
        $this->_time = preg_replace('/[\s]+/','', $this->_time);
        
        list($start, $end) = explode('-', trim($this->_time));

        if (stripos($start, 'am') === false && stripos($start, 'pm') === false) {
            if ($start >= 12) {
                $start -= 12;
            }
            $startSuffix = $start . 'pm';
        } else {
            $startSuffix = $start;
        }
        
        if (stripos($end, 'am') === false && stripos($end, 'pm') === false) {
            if ($end >= 12) {
                $end -= 12;
            }
            $endSuffix = $end . 'pm';
        } else {
            if (stripos($end, 'am') !== false) {
                $endSuffix = '11:59:59pm';
            } else {
                $endSuffix = $end;
            }
        }
        
        $this->_start = date('H:i:s', strtotime($startSuffix));
        $this->_end = date('H:i:s', strtotime($endSuffix));
        
        if ($this->_start >= $this->_end) {
            $this->_end = '23:59:59';
        }
        
    }
    
    public function getStart() {
        if (!$this->_start) {
            $this->_expand();
        }
        return $this->_start;
    }
    
    public function getEnd() {
        if (!$this->_end) {
            $this->_expand();
        }
        return $this->_end;
    }
    
}