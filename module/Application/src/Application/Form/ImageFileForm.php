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
        $this->add(new Csrf('csrf'));

        $this->add(array(
            'name' => 'image',
            'options' => array(
            ),
            'attributes' => array(
                'style' => 'display:none',
            ),
            'type'  => 'Zend\Form\Element\File',
        ));

        $this->add(array(
            'name' => 'fbutton',
            'options' => array(
                'label' => '画像を選択',
                'label_options' => array(
                    'disable_html_escape' => true,
                )
            ),
            'attributes' => array(
                'class' => 'btn btn-info',
                'onclick' => '$("input[id=lefile]").click();'
            ),
            'type'  => 'Zend\Form\Element\Button',
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => '作成'
            ),
            'type'  => 'Zend\Form\Element\Submit',
        ));

    }

}