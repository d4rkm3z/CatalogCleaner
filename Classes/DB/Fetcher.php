<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 27.04.17
 * Time: 14:42
 */

namespace DB;

class Fetcher extends Connector
{
    protected $table = 'products';

    protected function getQuery()
    {
        return "SELECT 
                  main.`product_key`,
                  c.name as color,
                  cl.name as class,
                  g.name as `group`
                FROM {$this->table} as main
                  LEFT JOIN colors as c ON main.color_id = c.id
                  LEFT JOIN brands as b ON main.brand_id = b.id
                  LEFT JOIN classes as cl ON main.class_id = cl.id
                  LEFT JOIN vendor_codes as vc ON main.vendor_code_id = vc.id 
                  LEFT JOIN groups as g ON main.group_id = g.id
                  LEFT JOIN images as i ON main.image_id = i.id";
    }

    public function fetchAllProducts()
    {
        return $this->dbh
            ->query($this->getQuery())
            ->fetchAll(\PDO::FETCH_ASSOC);
    }
}