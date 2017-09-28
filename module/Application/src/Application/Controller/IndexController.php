<?php

namespace Application\Controller;

use Application\Form\ImageFileForm;
use Application\Model\ImageFile;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $form = new ImageFileForm();
        $imageFile = new ImageFile();
        $form->bind($imageFile);
        $view = new ViewModel(array('form' => $form));
        return $view;
    }
}
