<?php

class Chris_Acl_UserRole implements Zend_Acl_Role_Interface
{
    
    public $role = '';
    
    /**
     * 
     * @param type $user
     */
    public function __construct($user)
    {
        
        if (is_string($user)) {
            
            $userConfiguration = Zend_Registry::get('UserConfiguration');
            $defaultRole = $userConfiguration->authentification->default->role;
            
            $this->setRoleId($defaultRole);
            
        } else {
            
            $role = strtolower($user->role);
            
            $this->setRoleId($role);
            
        }
        
    }
    
    /**
     * 
     * @param type $role
     */
    public function setRoleId($role)
    {
        
        $this->role = $role;
        
    }
    
    /**
     * 
     * @return type
     */
    public function getRoleId()
    {
        
        return $this->role;
        
    }
    
}