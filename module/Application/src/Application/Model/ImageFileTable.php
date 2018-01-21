<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class ImageFileTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function saveImageFile(ImageFile $imageFile, $ipaddr){
        $data = [
            'image_name' => $imageFile->imageName,
            'ipaddr' => $ipaddr,
        ];
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }

}