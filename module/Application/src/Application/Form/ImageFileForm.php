<?php

namespace Application\Form;

use Application\Model\ImageFile;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\File;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class ImageFileForm extends Form
{

    public function __construct()
    {
        parent::__construct('upload_form');
        $this->addElements();
    }

    public function addElements()
    {
        $this->setHydrator(new ClassMethods())->setObject(new ImageFile());
        $this->setAttribute('action', '/convert');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->add(new Csrf('csrf'));

        $this->add(array(
            'name' => 'image',
            'options' => array(
            ),
            'attributes' => array(
                'id' => 'hidden-file',
                'style' => 'display:none',
                'accept' => 'image/*',
            ),
            'type'  => 'Zend\Form\Element\File',
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'class' => 'btn btn-success col-md-12 btn-lg',
                'value' => '作成'
            ),
            'type'  => 'Zend\Form\Element\Submit',
        ));

    }

}