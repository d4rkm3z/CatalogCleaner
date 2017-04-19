<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 20.04.17
 * Time: 13:56
 */

namespace Models;

class CoreModel extends AbstractModel
{
    protected $rawData;
    protected $arrayForInsert;

    protected function setWhere($where = []){
        return [
            'vendor_code_id' => "(select id from vendor_codes where number={$where['vendor_id']})",
            'image_id' => "(select id from images where name='{$where['picture_name']}')",
        ];
    }

    public function save($arrayForInsert = []){
        if(count($arrayForInsert) == 0) return false;

        foreach ($arrayForInsert as $modelName => $modelContent){
            if($modelName == 'where') continue;
            $this->db->setTable($modelName);

            $modelName == 'products'
                ? $this->insert($modelContent, $this->setWhere($arrayForInsert['where']))
                : $this->insert($modelContent);
        }
    }
}