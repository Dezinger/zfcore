<?php
/**
 * Comments_IndexController for Comments module
 *
 * @category   Application
 * @package    Comments
 * @subpackage Controller
 *
 * @version  $Id: SubscribeController.php 2011-11-21 11:59:34Z pavel.machekhin $
 */
class Comments_IndexController extends Core_Controller_Action
{
    public function indexAction()
    {
        $postId = (int) $this->_getParam('postId', 0);
        $alias = $this->_getParam('alias');
        
        if (!$postId || !$alias) {
            throw new Zend_Controller_Action_Exception('Page not found');
        }
        
        $manager = new Comments_Model_Comment_Manager();
        $this->view->rowset = $manager->findAll($postId, $alias);
        $this->view->form = new Comments_Model_Comment_Form_Create();
        
        $user = $this->view->user();
        
        $this->view->form->setUser($user);
        
        if (!$user) {
            $this->view->loginForm = new Users_Model_Users_Form_Login();
        }
    }
}