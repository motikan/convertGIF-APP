<?php

namespace Application\Controller;

use Application\Form\ImageFileForm;
use Application\Model\ImageFile;
use Application\Utility\ImageUtility;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;

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

    /**
     * 画像をGIFに変換
     * POST /convert
     *
     * @return \Zend\Http\Response|ViewModel
     */
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
                $insertItemId = $this->getImageFileTable()->saveImageFile($imageFile, $ipAddress);

                // GIF画像の生成
                $resultGifFileName = ImageUtility::convertGif($insertItemId, $imageFile->imageName);

                return $this->redirect()->toUrl("/result/" . $resultGifFileName);
            }
            $view = new ViewModel([
                'form' => $form,
                'errors' => $form->getMessages(),
            ]);
            $view->setTemplate('application/index/index.phtml');
            return $view;
        }
        return $this->redirect()->toRoute('home');
    }

    public function resultAction()
    {
        $gifFileName = $this->params()->fromRoute('gif', 'none');
        $data = [
            'gifFileName' => $gifFileName,
        ];
        return new ViewModel($data);
    }

}