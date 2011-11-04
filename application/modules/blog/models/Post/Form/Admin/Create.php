<?php
/**
 * Login form
 *
 * @category Application
 * @package Model
 * @subpackage Form
 *
 * @version  $Id: Login.php 1561 2009-10-16 13:31:31Z dark $
 */
class Blog_Model_Post_Form_Admin_Create extends Zend_Form
{
    /**
     * Form initialization
     *
     * @return void
     */
    public function init()
    {
        $this->setName('postForm');
        $this->setMethod('post');


        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
            ->setRequired(true)
            ->setAttribs(array('style'=>'width:60%'))
            ->addValidator('regex',
                false,
                array('/^[\w\s\'",.\-_]+$/i', 'messages' => array (
                    Zend_Validate_Regex::INVALID => 'Invalid title',
                    Zend_Validate_Regex::NOT_MATCH  => 'Invalid title'
                )));
        $this->addElement($title);

//        $this->addElement(
//            'ValidationTextBox', 'title',
//            array(
//                'label'      => 'Title',
//                'required'   => true,
//                'attribs'    => array('style'=>'width:60%'),
//                'regExp'     => '^[\w\s\'",.\-_]+$',
//                'validators' => array(
//                    array(
//                        'regex',
//                        false,
//                        array('/^[\w\s\'",.\-_]+$/i', 'messages' => array (
//                            Zend_Validate_Regex::INVALID => 'Invalid title',
//                            Zend_Validate_Regex::NOT_MATCH  => 'Invalid title'
//                        ))
//                    ),
//                )
//            )
//        );


        $teaser = new Core_Form_Element_Redactor('teaser');
        $teaser->setLabel('Teaser')
            ->setAttribs(array('style' => 'width:100%;height:100px'));
        $this->addElement($teaser);

//        $this->addElement(
//            'Editor', 'teaser',
//            array(
//                'label'      => 'Teaser',
//                'required'   => true,
//                'attribs'    => array('style' => 'width:100%;height:100px'),
//                'plugins'    => array('undo', 'redo', 'cut', 'copy', 'paste', '|',
//                                      'bold', 'italic', 'underline', 'strikethrough', '|',
//                                      'subscript', 'superscript', 'removeFormat', '|',
//                                      //'fontName', 'fontSize', 'formatBlock', 'foreColor', 'hiliteColor','|',
//                                      'indent', 'outdent', 'justifyCenter', 'justifyFull',
//                                      'justifyLeft', 'justifyRight', 'delete', '|',
//                                      'insertOrderedList', 'insertUnorderedList', 'insertHorizontalRule', '|',
//                                      //'LinkDialog', 'UploadImage', '|',
//                                      //'ImageManager',
//                                      'FullScreen', '|',
//                                      'ViewSource')
//            )
//        );

        $teaser = new Core_Form_Element_Redactor('body');
        $teaser->setLabel('Text')
            ->setAttribs(array('style' => 'width:100%;height:340px'));
        $this->addElement($teaser);

//        $this->addElement(
//            'Editor', 'body',
//            array(
//                'label'      => 'Text',
//                'required'   => true,
//                'attribs'    => array('style' => 'width:100%;height:340px'),
//                'plugins'    => array('undo', 'redo', 'cut', 'copy', 'paste', '|',
//                                      'bold', 'italic', 'underline', 'strikethrough', '|',
//                                      'subscript', 'superscript', 'removeFormat', '|',
//                                      //'fontName', 'fontSize', 'formatBlock', 'foreColor', 'hiliteColor','|',
//                                      'indent', 'outdent', 'justifyCenter', 'justifyFull',
//                                      'justifyLeft', 'justifyRight', 'delete', '|',
//                                      'insertOrderedList', 'insertUnorderedList', 'insertHorizontalRule', '|',
//                                      //'LinkDialog', 'UploadImage', '|',
//                                      //'ImageManager',
//                                      'FullScreen', '|',
//                                      'ViewSource')
//            )
//        );

        $published = new Zend_Form_Element_Text('published');
        $published->setLabel('Published Date')
            ->setRequired(true);
        $this->addElement($published);

//        $this->addElement(
//            'DateTextBox', 'published',
//            array(
//                'label'      => 'Published Date',
//                'required'   => true
//            )
//        );

        $this->addElement($this->_category());

        $this->addElement($this->_status());

        $this->addElement($this->_user());


        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save');
        $this->addElement($submit);

//        $this->addElement(
//            'SubmitButton',
//            'submit',
//            array(
//                'label' => 'Save'
//            )
//        );

        $this->addElement(new Zend_Form_Element_Hidden('pid'));

//        Zend_Dojo::enableForm($this);
    }

    /**
     * Category Combobox
     *
     * @return Zend_Form_Element_Select
     */
    protected function _category()
    {
        $categories = new Forum_Model_Category_Manager();

        $options = array();
        foreach ($categories->getAll() as $category) {
            $options[$category->id] = $category->title;
        }

        $element = new Zend_Form_Element_Select('categoryId');
        $element->setLabel('Category')
                ->setRequired(true)
                ->addMultioptions($options)
                ->setAttribs(array('style' => 'width:60%'));


        return $element;
    }

    /**
     * Status combobox
     *
     * @return Zend_Form_Element_Select
     */
    protected function _status()
    {
        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('Status')
                ->setRequired(true)
                ->setAttribs(array('style' => 'width:60%'))
                ->addMultioptions(
            array(
                 Forum_Model_Post::STATUS_ACTIVE => Forum_Model_Post::STATUS_ACTIVE,
                 Forum_Model_Post::STATUS_CLOSED => Forum_Model_Post::STATUS_CLOSED,
                 Forum_Model_Post::STATUS_DELETED => Forum_Model_Post::STATUS_DELETED,
            )
        );

        return $status;
    }

    /**
     * User Combobox
     *
     * @return Zend_Form_Element_Select
     */
    protected function _user()
    {
        $users = new Users_Model_Users_Table();
        $res = array();
        foreach ($users->fetchAll() as $row) {
            $res[$row->id] = $row->login;
        }

        $element = new Zend_Form_Element_Select('userId');
        $element->setLabel('Author')
                ->setRequired(true)
                ->setAttribs(array('style' => 'width:60%'))
                ->addMultioptions($res);
        return $element;
    }
}