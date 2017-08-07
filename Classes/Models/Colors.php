<?php

namespace Models;

class Colors extends Model
{
    protected $table = 'colors';

    public function insert(array $data): void
    {
        $this->db->append([
            'product_key' => $data['product_key'],
            'name' => $data['color'],
            'color_id' => $data['color_id'],
        ]);
    }

    public function loadDistinctColorNames()
    {
        return $this->db->fetchAllByColumn(['name'],
            " WHERE name NOT REGEXP '^[0-9]' GROUP BY name ");
    }
}