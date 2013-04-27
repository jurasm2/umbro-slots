<?php

namespace Model;

final class UserModel extends BaseModel {
    
    public function __construct($context) {
        parent::__construct($context);
    }
    
    public function getUser($id) {
        return $this->connection->fetch("SELECT * FROM [reg2_users] WHERE [user_id] = %i", $id);
    }
    
    public function addUser($data) {
        
        $result = 'ERROR::Unknown error';
        unset($data['password2']);
        $data['registration_complete'] = 1;
        
        $result = NULL;
        
        try {
            $this->connection->query('INSERT INTO [reg2_users]', $data);
            $result = $this->connection->getInsertId();
        } catch (\DibiException $ex) {
            $result = NULL;
        }
        
        return $result;
        
    }
    
     public function editUser($data) {
        $result = 'ERROR::Unknown error';       
        
        $userId = $data['user_id'];
                
        unset($data['user_id']);        
        unset($data['password2']);      
        try {
            $result = $this->connection->query('UPDATE [reg2_users] SET ', $data, 'WHERE [user_id] = %i', $userId);            

            if ($result > 0) $result = 1;
        } catch (\DibiException $ex) {
            $result = 'ERROR::'.$ex->getMessage();
        }
        
        return $result;
    }
    
     
    
    public function batchActivateUsers($uids, $teamId) {
        return $this->batchOperationOnUsers($uids, $teamId, 'activateOperation');
    }
    
    public function batchBlockUsers($uids, $teamId) {
        return $this->batchOperationOnUsers($uids, $teamId, 'blockOperation');
    }
    
    public function batchOperationOnUsers($uids, $teamId, $operation) {
        
        $result = 'ERROR::Unknown error';       
                        
        $membership = 'blocked';
        
        switch ($operation) {
            case 'activateOperation': $membership = 'active'; break;
            case 'blockOperation': $membership = 'blocked'; break;
        }
        
        try {
            
            $result = $this->connection->query("UPDATE [is_in_team] SET membership = %s WHERE team_id = %i AND user_id IN %in", $membership, $teamId, $uids);
            
            if ($result > 0) $result = 1;
        } catch (\DibiException $ex) {
            $result = 'ERROR::'.$ex->getMessage();
        }
        
        return $result;
        
    }
    
    public function deleteUser($id) {
        return $this->connection->query('DELETE FROM [reg2_users] WHERE id = %i', $id);
    }
    
    

    
    
    /* REGISTRATION */
    
    public function register($data) {
        return $this->connection->query('INSERT INTO [reg2_users]', $data);
    }

    
    public function updateProfile($data, $userId) {
        return $this->connection->query('UPDATE [reg2_users] SET', $data, 'WHERE [user_id] = %i', $userId);
    }
    
    public function updateUser($userId, $data) {
        return $this->connection->query('UPDATE [reg2_users] SET ',$data,' WHERE [user_id] = %i', $userId);
    }
    
    public function getUserByUrlChunk($urlChunk) {
        return $this->connection->fetch('SELECT * FROM [reg2_users] WHERE [url_chunk] = %s', $urlChunk);
    }
    
    /* RATING */
    
    public function getIp() {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    public function userHasRatedUser($userId) {
        $ip = $this->getIp();
        
        $where = array(
                    'rated_user_id' =>  $userId,
                    'ip'            =>  $ip
        );
        
        $c = $this->connection->fetchSingle('SELECT COUNT(*) FROM [ratings] WHERE %and', $where);
        return $c > 0;
    }
    
    public function registerRating($userId) {
        $data = array(
                    'rated_user_id' =>  $userId,
                    'ip'            =>  $this->getIp()
        );
        return $this->connection->query('INSERT INTO [ratings]', $data);
    }
    
    public function incPlusRating($userId) {        
        $this->connection->query('UPDATE [reg2_users] SET [rating_plus] = [rating_plus] + 1 WHERE [user_id] = %i', $userId);
        return $this->registerRating($userId);
    }
    
    public function incMinusRating($userId) {
        $this->connection->query('UPDATE [reg2_users] SET [rating_minus] = [rating_minus] + 1 WHERE [user_id] = %i', $userId);
        return $this->registerRating($userId);
    }
}