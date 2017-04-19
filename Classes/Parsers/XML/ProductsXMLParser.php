<?php
namespace Parsers\XML;

use Logs\Logs;
use Models\CoreModel;
use Reformers\ProductReformer;

class ProductsXMLParser extends AbstractParser
{
    protected $model;
    protected $reformer;

    protected function setActiveClasses(){
        $this->model = new CoreModel();
        $this->reformer = new ProductReformer();
    }

    protected function parseNode($node){
        if (count($node)) {
            $parsedArray = $this->reformer->reformat($node);
            $this->model->save($parsedArray);
        }
    }

    protected function checkNodeType(){

    }

    /**
     * Load products from xml file from eSales format
     *
     * @param $pathToXML
     * @return array
     */
    public function parseXML()
    {
        $xml = $this->xml;
        $variantsAttributes = false;
        $parseVariants = true;

        while ($xml->read()) {
            $nodeName = strtolower($xml->name);

            $isProductStart = $xml->nodeType == \XMLReader::ELEMENT && $nodeName == 'product';
            $isVariantStart = $parseVariants && !$isProductStart && $xml->nodeType == \XMLReader::ELEMENT && $nodeName == 'variant';

            if ($isProductStart || $isVariantStart) {
                $this->setActiveClasses();

                $this->parseNode(static::_xmlToArray($xml->readInnerXML()));
                $xml->next();

//                $node = static::_xmlToArray($xml->readInnerXML(), $variantsAttributes);
//                $this->setActiveModel("variants");
//                $this->parsingNode($node);
            }



            /*if ($isVariantStart) {
                $variant = static::_xmlToArray($xml->readInnerXML(), $variantsAttributes);
                $variantKey = isset($variant['variant_key']) ? $variant['variant_key'] : false;
                if ($variantKey) {
                    $variant['key'] = $variantKey;
                    unset($variant['variant_key']);
                } else {
                    continue;
                }

                $productKey = isset($variant['product_key']) ? $variant['product_key'] : false;
                if ($productKey) {
                    unset($variant['product_key']);

                    if (!isset($variants[$productKey])) {
                        //$variants[$productKey] = [];
                        $products[$productKey]['variants'] = [];
                    }
                    $variants[$productKey][$variantKey] = $variant;
                    $products[$productKey]['variants'][$variantKey] = $variant;
                }
                unset($variant, $variantKey, $productKey);
                $xml->next();
            }

            if (count($products) > 11000){
                printf(count($products) . "\n");
            }*/
        }

        $xml->close();
        Logs::write("The xml is parsed");
    }
}