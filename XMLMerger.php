<?php

class XMLMerger
{
    private const DATA_FOLDER = 'spare_parts_feed/*[0-9].json';

    private function getDataFolder($file)
    {
        $xml = json_decode(file_get_contents($file), true);
        return $xml;
    }

    public function setDataReverse()
    {
        $newXml = [];
        $temp_files = glob(self::DATA_FOLDER);
        foreach($temp_files as $file) {
            $xml = self::getDataFolder($file);

            $fvMake = explode(' / ', $xml['vehicle']['name']);
            $vMake = $fvMake[0];
            $vModel = !isset($fvMake[1]) ? '-' : $fvMake[1];

            foreach ($xml['categories'] as $category3) {
                if (!empty($category3['categories'])) {
                    foreach ($category3['categories'] as $category4) {
                        foreach ($category4['spare_parts'] as $spare_part) {
                            $product = $spare_part['product'];
                            $newProduct = [
                                'name' => $product['name'],
                                'product_no' => $product['product_no'],
                                'vat_percent' => $product['vat_percent'],
                                'unit_price_incl_vat' => $product['unit_price_incl_vat'] ?? 0,
                                'categories' => [[
                                    'name' => $category4['name'],
                                    'categories' => [[
                                        'name' => $category3['name'],
                                        'categories' => [[
                                            'model_name' => $vModel,
                                            'categories' => [['make_name' => $vMake]]
                                        ]]
                                    ]]]
                                ]
                            ];

                            if (!isset($newXml[$product['product_no']])) {
                                $newXml[$product['product_no']] = $newProduct;
                            } else {
                                $cat4 = $newXml[$product['product_no']]['categories'];
                                $keyCat4 = array_search($category4['name'], array_column($cat4, 'name'));
                                if ($keyCat4 !== false) {
                                    $keyCat3 = array_search($category3['name'], array_column($cat4[$keyCat4]['categories'], 'name'));
                                    if ($keyCat3 !== false) {
                                        $newXml[$product['product_no']]['categories'][$keyCat4]['categories'][$keyCat3]['categories'][] = [
                                            'model_name' => $vModel,
                                            'categories' => [
                                                'make_name' => $vMake,
                                            ]
                                        ];
                                    } else {
                                        $newXml[$product['product_no']]['categories'][$keyCat4]['categories'][] = [
                                            'name' => $category3['name'],
                                            'categories' => [
                                                'model_name' => $vModel,
                                                'categories' => [
                                                    'make_name' => $vMake,
                                                ]
                                            ]
                                        ];
                                    }
                                } else {
                                    $newXml[$product['product_no']]['categories'][] = [
                                        'name' => $category4['name'],
                                        'categories' => [
                                            'name' => $category3['name'],
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
        }

        return $newXml;
    }
}