<?php
/**
 * Model Comment
 *
 * @category Application
 * @package Comments
 * @subpackage Model
 * 
 * @version  $Id: Comment.php 2011-11-21 11:59:34Z pavel.machekhin $
 */
class Comments_Model_Comment extends Core_Db_Table_Row_Abstract
{
    /**
     * Allows pre-insert logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return  void
     */
    public function _insert()
    {
        $this->created = date("Y-m-d h:i:s");

        if (!$this->userId) {
            $identity = Zend_Auth::getInstance()->getIdentity();
            $this->userId = $identity->id;
        }
        $this->_update();
    }

    /**
     * Allows pre-update logic to be applied to row.
     * Subclasses may override this method.
     *
     * @return void
     */
    public function _update()
    {
        $this->updated = date("Y-m-d h:i:s");
    }
    
    /**
     * Get user name
     *
     * @return string | null
     */
    public function getUserName()
    {
        if ($this->firstname && $this->lastname) {
            return $this->firstname . ' ' . $this->lastname;
        } else if ($this->login) {
            return $this->login;
        } else {
            return null;
        }
    }
}