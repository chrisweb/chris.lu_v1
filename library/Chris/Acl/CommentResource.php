<?php

class Chris_Acl_CommentResource implements Zend_Acl_Resource_Interface
{
    
    public $resourceId = null;
    public $commentId = null;
    public $ownerId = null;
    
    /**
     * 
     * @param type $comment
     * @param type $article
     */
    public function __construct($comment)
    {

        $this->setResourceId('comment');
        $this->setCommentId((string) $comment['_id']);
        
        if (array_key_exists('user_id', $comment)) {
            
            $this->setOwnerId((string) $comment['user_id']);
            
        }
        
    }
    
    /**
     * 
     * @param type $id
     */
    public function setResourceId($resourceId)
    {
        
        $this->resourceId = $resourceId;
        
    }
    
    /**
     * 
     * @return type
     */
    public function getResourceId()
    {
        
        return $this->resourceId;
        
    }
    
    /**
     * 
     * @param type $id
     */
    public function setCommentId($commentId)
    {
        
        $this->commentId = $commentId;
        
    }
    
    /**
     * 
     * @return type
     */
    public function getCommentId()
    {
        
        return $this->commentId;
        
    }
    
    /**
     * 
     * @param type $ownerId
     */
    public function setOwnerId($ownerId)
    {
        
        $this->ownerId = $ownerId;
        
    }
    
    /**
     * 
     * @return type
     */
    public function getOwnerId()
    {
        
        return $this->ownerId;
        
    }
    
}