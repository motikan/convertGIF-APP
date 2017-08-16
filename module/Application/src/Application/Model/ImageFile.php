<?php

namespace Application\Model;

use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ImageFile implements InputFilterAwareInterface
{
    public $id;
    public $imageName;
    public $ipaddr;
    public $createAt;
    public $deleteAt;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->imageName  = (isset($data['image']['name'])) ? $data['image']['name'] : '';
        $this->ipaddr = (isset($data['ipaddr'])) ? $data['ipaddr'] : '';
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'image',
                'required' => true,
                'error_message' => '画像を選択してください',
                'validators' => array(
                    array (
                        'name' => 'fileisimage',
                    ),
                    array(
                        'name' => 'filesize',
                        'options' => array(
                            'max' => '4MB'
                        ),
                    ),
                ),
            ));

            $fileInput = new FileInput('image');
            $fileInput->setRequired(true);
            $fileInput->getFilterChain()->attachByName(
                'filerenameupload',
                array(
                    'target'    => './data/',
                    'randomize' => false,
                    "use_upload_name" => true,
                    "use_upload_extension" => true
                )
            );

            $inputFilter->add($fileInput);

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}