<?php

namespace Reformers;

class ProductReformer extends AbstractReformer
{
    function __construct()
    {
        $this->setDataMap();
    }

    protected function setDataMap()
    {
        $this->dataMap = [
            'brands' => [
                'id' => 'brand_id',
                'name' => 'brand_name',
            ],
            'colors' => [
                'id' => 'color_id',
                'name' => [
                    'color_16',
                    'color_8',
                    'color_1'
                ],
            ],
            'classes' => [
                'id' => 'class_id',
                'name' => [
                    'class_16',
                    'class_8',
                    'class_1'
                ],
            ],
            'vendor_codes' => [
                'number' => 'art_no',
                'number_full' => 'article_nr',
                'number_short' => 'article_nr_short',
            ],
            'groups' => [
                'id' => 'group_id',
                'name' => [
                    'group_16',
                    'group_8',
                    'group_1'
                ],
            ],
            'images' => [
                'name' => 'picture_name',
                'local_name' => 'image_local',
            ],
            'sizes' => [
                'id' => 'size_id',
                'name' => 'size_name',
            ],
            'where' => [
                'vendor_id' => 'art_no',
                'picture_name' => 'picture_name',
            ],
            'variants' => [
                'key' => 'variant_key',
                'product_key' => 'product_key',
                'group_size_ids' => 'group_size_ids',
                'size_id' => 'size_id',
                'stock' => 'stock',
            ],
            'products' => [
                'brand_id' => 'brand_id',
                'color_id' => 'color_id',
                'class_id' => 'class_id',
                'group_id' => 'group_id',

                'product_key' => '*',
                'product_description' => [
                    'product_description_16',
                    'product_description_8',
                    'product_description_1',
                ],
                'product_name' => [
                    'product_name_16',
                    'product_name_8',
                    'product_name_1',
                ],
                'product_type' => '*',
                'product_show_in_list' => 'show_product_in_list',
                'active' => '*',
                'channel_ids' => '*',
                'code_id' => '*',
                'create_date' => '*',
                'current_price' => [
                    'current_price_16',
                    'current_price_8',
                    'current_price_1',
                ],
                'default_color' => '*',
                'in_stock' => '*',
                'newsvalue' => '*',
                'site_ids' => '*',
                'sort_date' => [
                    'sort_date_16',
                    'sort_date_8',
                    'sort_date_1',
                ],
                '_type' => '*',
                'show' => '*',
                'price' => '*',
                'original_price' => [
                    'original_price_16',
                    'original_price_8',
                    'original_price_1'
                ],
                'price_formatted' => '*',
                'in_sales' => [
                    'in_sales_16',
                    'in_sales_8',
                    'in_sales_1',
                ],
                'in_sales_bool' => [
                    'in_sales_bool_16',
                    'in_sales_bool_8',
                    'in_sales_bool_1',
                ],
                'barcodes' => '*'
            ]
        ];
    }
}