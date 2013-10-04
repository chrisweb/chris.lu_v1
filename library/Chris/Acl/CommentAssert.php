<?php

class Chris_Acl_CommentAssert implements Zend_Acl_Assert_Interface
{
    
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {   

        if (!$resource instanceof App_Model_UserOwnedInterface) {
            
            throw new Exception('Resource is not an instance of Article_Model_MongoDB_Comment');
            
        }

        return $resource->isOwner($user);
        
    }
    
}