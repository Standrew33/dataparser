<?php

class XMLMerger
{
    private const DATA_FILE = 'spare_parts_feed/1085.xml';

    public function getDataFolder()
    {
        $xml = simplexml_load_file(self::DATA_FILE);

        return $xml;
    }

    public function setDataReverse($xml)
    {
        $fvMake = explode(' / ', $xml->vehicle->name);
        $vMake = $fvMake[0];
        $vModel = $fvMake[1];
        $newXml = [];

        foreach ($xml->categories->category as $category3) {
            if (!empty($category3->categories)) {
                foreach ($category3->categories->category as $category4) {
                    foreach ($category4->spare_parts as $spare_part) {
                        $product = $spare_part->product;
                        $newProduct = [
                            $product->product_no => [
                                'name' => $product->name,
                                'product_no' => $product->product_no,
                                'vat_percent' => $product->vat_percent,
                                'unit_price_incl_vat' => $product->unit_price_incl_vat ?? 0,
                                'categories' => [
                                    'name' => $category4->name,
                                    'categories' => [
                                        'name' => $category3->name,
                                        'categories' => [
                                            'model_name' => $vModel,
                                            'categories' => [
                                                'make_name' => $vMake,
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ];

                        if (!isset($newXml[$spare_part->product->product_no])) {
                            $newXml[] = $newProduct;
                        } else {
                            $keyCat4 = array_search($category4->name, $newXml[$product->product_no]['categories']);
                            if ($keyCat4 != false) {
                                $keyCat3 = array_search($category4->name, $newXml[$product->product_no]['categories'][$keyCat4]);
                                if ($keyCat3 != false) {
                                    $newXml[$product->product_no]['categories'][$keyCat4]['categories'][$keyCat3]['categories'][] = [
                                        'model_name' => $vModel,
                                        'categories' => [
                                            'make_name' => $vMake,
                                        ]
                                    ];
                                } else {
                                    $newXml[$product->product_no]['categories'][$keyCat4]['categories'][] = [
                                        'name' => $category3->name,
                                        'categories' => [
                                            'model_name' => $vModel,
                                            'categories' => [
                                                'make_name' => $vMake,
                                            ]
                                        ]
                                    ];
                                }
                            } else {
                                $newXml[$product->product_no]['categories'][] = [
                                    'name' => $category4->name,
                                    'categories' => [
                                        'name' => $category3->name,
                                        'categories' => [
                                            'model_name' => $vModel,
                                            'categories' => [
                                                'make_name' => $vMake,
                                            ]
                                        ]
                                    ]
                                ];
                            }
                        }
                    }
                }
            } else {

            }
        }

        return $newXml;
    }
}