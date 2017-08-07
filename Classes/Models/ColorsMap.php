<?php

namespace Models;

use Helpers\Parsers\Colors\IColorsParser;

class ColorsMap extends Model
{
    protected $table = 'colors_map';
    protected $colorsList = [];
    protected $data;

    protected function cleanInputData()
    {
        foreach ($this->data as $key => $val) {
            if (empty($val['hex'])) unset($this->data[$key]);
        }
    }

    protected function prepareUpdateData(array $data): string
    {
        $queries = [];
        foreach ($data as $val) {
            $queries[] = "WHEN name = '{$val['name']}' THEN '{$val['hex']}'";
        }
        return implode(' ', $queries);
    }

    public function getColorNames()
    {
        return $this->db->fetchAllByColumn(['name'],
            " WHERE name NOT REGEXP '^[0-9]' AND hex='' ORDER BY name");
    }

    public function updateColors(): void
    {
        foreach ($this->data as $key => $val) {
            $this->db->update([
                'hex' => $val['hex']
            ], "name = '{$val['name']}'");
        }
    }

    public function setColors()
    {
        $this->colorsList = $this->getColorNames();
    }

    public function findColorsHex(IColorsParser $parser)
    {
        foreach ($this->colorsList as $colorName) {
            $hex = $parser->parse($colorName);
            $this->data[] = [
                'name' => $colorName,
                'hex' => $hex
            ];
        }

        $this->cleanInputData();
        $this->updateColors();
    }
}