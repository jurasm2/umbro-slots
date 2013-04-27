<?php

namespace FrontModule;


class DefaultPresenter extends BasePresenter {

    public $currentDay = 'today';
    
    public function handleGetSlots($date) {
        
        $this->currentDay = $date;
        
        $this->invalidateControl('slots');
    }

    public function handleReserve($startTime) {
        
    }
    
    
    private function _isSaturday($timestamp) {
        
        return date('N', $timestamp) == 6;
        
    }
    
    public function beforeRender() {
        parent::beforeRender();
        
        $params = $this->context->parameters;
        
        if (strtotime($this->currentDay) < strtotime($params['minDate'])) {
            $this->currentDay = $params['minDate'];
        }
        
        
        
        $slotLength = $params['slotLength'];
        
        $isSat = $this->_isSaturday(strtotime($this->currentDay));

        $startTime = $isSat ? $params['startTimeSat'] : $params['startTime'];
        $endTime = $isSat ? $params['endTimeSat'] : $params['endTime'];
        
        $numberOfSlotsInDay = ceil((strtotime($endTime, 0) - strtotime($startTime, 0) + 1) / strtotime($slotLength, 0));
        $disabledDays = $this->slotModel->getDisabledDays($numberOfSlotsInDay);
        
        $disabledDays = array_keys($disabledDays);
        
        array_walk($disabledDays, function(&$item) {
            $item = date('Y-n-j', strtotime($item));
        });
        
//        dump($disabledDays);
//        die();
        
        //$disabledDays = array('2013-1-5');
        
        $this->template->slotLengthInSeconds = strtotime('+'.$slotLength,0);
        $this->template->minDate = time() > strtotime($params['minDate']) ? 0 : date('Y-m-d', strtotime($params['minDate']));
        $this->template->maxDate = date('Y-m-d', strtotime($params['maxDate']));
        
        $this->template->disabledDays = json_encode($disabledDays);
        
        $link = $this->link('getSlots!');
        $this->template->getSlotsLink = $link;
        
    }
    
    public function renderDefault() {
        
        $params = $this->context->parameters;
        
        $isSat = $this->_isSaturday(strtotime($this->currentDay));

        $startTime = $isSat ? $params['startTimeSat'] : $params['startTime'];
        $endTime = $isSat ? $params['endTimeSat'] : $params['endTime'];
        
//        $startTime = $params['startTime'];
//        $endTime = $params['endTime'];

        $slotLength = $params['slotLength'];
              
        $dbSlots = $this->slotModel->getSlots($this->currentDay);
        
        $reservedSlots = array();
        if ($dbSlots) {
            $reservedSlots = array_keys($dbSlots);
        }
        
//        dump($reservedSlots);
//        die();
        
        $slots = array();
        
        $temp = strtotime($this->currentDay . ' ' . $startTime);
        
        while ($temp <= strtotime($this->currentDay . ' ' . $endTime)) {
            
            $tempTimestamp = date('Y-m-d H:i:s',$temp);
            
//            dump(date('Y-m-d H:i:s',$temp));
//            die();
            
            $slots[] = array(
                        'start_time'    => $temp,
                        'end_time'      => strtotime('+'.$slotLength, $temp),
                        'reserved'      =>  array_search($tempTimestamp, $reservedSlots) !== FALSE
            );
            $temp = strtotime('+'.$slotLength, $temp);
        }
        
        $this->template->slots = $slots;
        
//        dump(date('j.n.Y', strtotime($this->currentDay)));
//        die();
        $this->template->currentDay = date('j.n.Y', strtotime($this->currentDay));
    }

}