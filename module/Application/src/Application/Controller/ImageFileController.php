<?php

namespace Application\Controller;

use Application\Form\ImageFileForm;
use Application\Model\ImageFile;
use Application\Utility\ImageUtility;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ImageFileController extends AbstractRestfulController
{

    protected $imageFileTable;

    public function getImageFileTable()
    {
        if(!$this->imageFileTable){
            $sm = $this->getServiceLocator();
            $this->imageFileTable = $sm->get('Application\Model\ImageFileTable');
        }
        return $this->imageFileTable;
    }

    public function convertAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new ImageFileForm();
            $imageFile = new ImageFile();
            $form->setInputFilter($imageFile->getInputFilter());


            // 画像ファイルの変更
            $file = $this->getRequest()->getFiles();
            if($file['image']['name'] != ""){
                $ext = substr($file['image']['name'],strrpos($file['image']['name'], '.') + 1);
                $fileName = uniqid() . "." . $ext;
                $file->image['name'] = $fileName;
            }

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $file->toArray()
            );

            $form->setData($post);

            // 妥当性チェック
            if ($form->isValid()) {
                $imageFile->exchangeArray($post);
                $ipAddress = $request->getServer('REMOTE_ADDR');
                $this->getImageFileTable()->saveImageFile($imageFile, $ipAddress);

                ImageUtility::convertGif($imageFile->imageName);

                return $this->redirect()->toRoute("home");
            }

            return new JsonModel($form->getMessages());
        }
        return $this->redirect()->toRoute('home');
    }

}