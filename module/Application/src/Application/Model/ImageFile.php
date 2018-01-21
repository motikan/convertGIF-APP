<?php

namespace Application\Model;

use Application\Utility\ImageUtility;
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

            $inputFilter->add([
                'name' => 'image',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'fileisimage',
                    ],
                    [
                        'name' => 'filesize',
                        'options' => [
                            'max' => '4MB'
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'speed',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'InArray',
                        'break_chain_on_failure' => true,
                        'options' => [
                            'haystack' => ImageUtility::getTransSpeedKeysArray(),
                        ],
                    ],
                    [
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                    ],
                ],
            ]);

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