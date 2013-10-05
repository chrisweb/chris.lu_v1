<?php

class Chris_Acl_CommentAssert implements Zend_Acl_Assert_Interface
{
    
    /**
     * 
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $role
     * @param Zend_Acl_Resource_Interface $resource
     * @param type $privilege
     * @return boolean
     */
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {   

        //Zend_Debug::dump($acl, '$acl: ');
        //Zend_Debug::dump($role, '$role: ');
        //Zend_Debug::dump($resource, '$resource: ');
        //Zend_Debug::dump($privilege, '$privilege: ');
        //exit;
        
        $auth = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity()) {

            $user = $auth->getIdentity();
            
            $userId = (string) $user->_id;
            
        } else {
            
            return false;
            
        }
        
        if ($role->role === 'admin') {
            
            return true;
            
        }
        
        //Zend_Debug::dump($resource->ownerId, '$resource->ownerId: ');
        //Zend_Debug::dump($userId, '$userId: ');
        
        if (!is_null($resource->ownerId) && $resource->ownerId === $userId) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
}