<?php

namespace Model;

final class SlotModel extends BaseModel {
   

    private function getSlot($startTime) {
        return $this->connection->fetch('SELECT * FROM [reg2_slots] WHERE [start_time] = %t', $startTime);
    }
    
    public function reserveSlot($formData) {
        
        $success = FALSE;
        
        $slotStartTime = $formData['start_time'];
        
        // check if this slot exists
        $slot = $this->getSlot($slotStartTime);
        
        if (!$slot) {
            
            // OK, slot does not exists
            // create it
            $formData['start_time%t'] = $formData['start_time'];
            unset($formData['start_time']);
            $this->connection->query('INSERT INTO [reg2_slots]', $formData);
            $success = TRUE;
        }
        
            
        return $success;
    }
    
    
    public function getSlots($currentDay) {
        
        $timestamp = strtotime($currentDay);
        
        return $this->connection->query('SELECT 
                                            * 
                                            FROM 
                                                [reg2_slots] 
                                            WHERE 
                                                DAY([start_time]) = %i 
                                                    AND 
                                                MONTH([start_time])=%i 
                                                    AND 
                                                YEAR([start_time]) = %i
                                        ', date('j', $timestamp), date('n', $timestamp), date('Y', $timestamp))->fetchAssoc('start_time');
        
    }
    
    public function deleteSlot($slotId) {
    
        return $this->connection->query('DELETE FROM [reg2_slots] WHERE [slot_id] = %i', $slotId);
    }
    
    public function getDisabledDays($n) {
        
        return $this->connection->query('SELECT DATE([start_time]) as [date], COUNT(*) as [count] FROM [reg2_slots] GROUP BY DATE([start_time]) HAVING [count] >= %i', $n)->fetchAssoc('date');
        
    }
    
    
    public function getSlotsByDate($timestamp) {
        
        return $this->connection->query('SELECT * FROM [reg2_slots] WHERE DATE([start_time]) = %d', $timestamp)->fetchAll();
        
    }
    
}