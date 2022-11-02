<?php

namespace Pimeo\Models;

class BaseAttributes
{
    public static function getArray()
    {
        $attributes = [
            [
                'label'     => [
                    'name'   => 'search_keywords',
                    'values' => [
                        'fr' => 'Mots clés de recherche',
                        'en' => 'Search keywords',
                    ],
                ],
                'attribute' => [
                    'company_id'          => 1,
                    'attribute_type_id'   => 25,
                    'name'                => 'search_keywords',
                    'model_type'          => 'parent_product',
                    'has_value'           => false,
                    'is_min_requirement'  => false,
                    'is_parent_attribute' => true,
                    'should_index'        => false,
                    'options'             => [
                        'special_index_key' => 'search_keywords',
                    ],
                ],
                'natures'   => [1, 2, 3, 4],
            ],
            [
                'label' => [
                    'name'   => 'name',
                    'values' =>
                    [
                        'en' => 'Product name',
                    ],
                ],
                'attribute' => [
                    'company_id'          => 1,
                    'attribute_type_id'   => 10,
                    'name'                => 'name',
                    'model_type'          => 'parent_product',
                    'has_value'           => false,
                    'is_min_requirement'  => true,
                    'is_parent_attribute' => true,
                    'options'             => [
                        'special_index_key' => 'name',
                    ],
                    'attribute_group_id' => null,
                    'should_index'       => false,
                ],
                'natures' => [1, 2, 3, 4],
            ],
            [
                'label' => [
                    'name'   => 'product_image',
                    'values' =>
                    [
                        'en' => 'Photo of the product',
                    ],
                ],
                'attribute' => [
                    'company_id'          => 1,
                    'attribute_type_id'   => 9,
                    'name'                => 'name',
                    'model_type'          => 'parent_product',
                    'has_value'           => false,
                    'is_min_requirement'  => false,
                    'is_parent_attribute' => true,
                    'options'             => [
                        'special_index_key' => 'file_image',
                    ],
                    'attribute_group_id' => null,
                    'should_index'       => false,
                ],
                'natures' => [1, 2, 3, 4],
            ],
            [
                'label' => [
                    'name'   => 'product_function',
                    'values' =>
                    [
                        'en' => 'Product roles',
                    ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 4,
                  'name'                => 'product_role',
                  'model_type'          => 'parent_product',
                  'has_value'           => true,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => true,
                  'options'             =>
                   [
                    'special_index_key' => 'list_type',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => true,
                ],
                'natures' => [1, 2, 3, 4],
                'value' =>  [
                  'attribute_id' => 5,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 =>
                        [
                        'name' => 'Accessory',
                        'image' => 'Accessoires.jpg7c1d6658e85d3793c79de7e3fbd8c2da1d16f392.jpg',
                        ],
                      1 =>
                        [
                        'name' => 'Adhesive',
                        'image' => 'Adhésifs.JPGb749ffc5e6da574bb3e044580454f7c728a6be97.JPG',
                        ],
                      2 =>
                       [
                        'name' => 'Primer',
                        'image' => 'Apprêt.jpga4f34cd1df16df5d5adeb6730eb7615febb06dc3.jpg',
                        ],
                      3 =>
                       [
                        'name' => 'Mechanical fasteners ',
                        'image' => 'Attaches_mécaniques.jpga7117c6268a03c6a222a139f77192d115808c223.jpg',
                        ],
                      4 =>
                       [
                        'name' => 'Cover stripe',
                        'image' => 'Bande_recouvrement.jpgc114cf78e86162f78d5cf1db687b4fa414b9a3dc.jpg',
                        ],
                      5 =>
                        [
                        'name' => 'Root barrier',
                        'image' => 'Barrière_Antiracines.jpg83efb453ddc9f45aeccac16be38bcba5868b58be.jpg',
                        ],
                      6 =>
                        [
                        'name' => 'Thermal barrier',
                        'image' => 'Barrière_thermique.JPG7cf5cdae29e3a724c90d5b510639770b534df4a0.JPG',
                        ],
                      7 =>
                        [
                        'name' => 'Wire and cable flashing',
                        'image' => 'Dispositif_SortieToiture.jpg9e03f881fa85985ca239355b54396527b3722483.jpg',
                        ],
                      8 =>
                        [
                        'name' => 'Drain',
                        'image' => 'Drain.jpgb5941b0ff0b1b62695bb3806967bd175ad60fd54.jpg',
                        ],
                      9 =>
                        [
                        'name' => 'Coating',
                        'image' => 'Enduits.JPG94e55ceb2fe21ed25486b0738213cff10b824e3f.JPG',
                        ],
                      10 =>
                        [
                        'name' => 'Equipment',
                        'image' => 'Équipement.jpg6906d15e6ab73e0c1e5d421adef432782c9d81e4.jpg',
                        ],
                      11 =>
                        [
                        'name' => 'Vent',
                        'image' => 'Évent.JPG0708cdc9cbe797454f11167c6a951afa4bf78300.JPG',
                        ],
                      12 =>
                        [
                        'name' => 'Geotextile',
                        'image' => 'Géotextile.jpgb3be87bb35218ecdb816007207230371488f6866.jpg',
                        ],
                      13 =>
                        [
                        'name' => 'Soundproofing',
                        'image' => 'Insonorisation.jpgdba6ec91d090375bf53f3921b80d694662f5784e.jpg',
                        ],
                      14 =>
                        [
                        'name' => 'Insulation',
                        'image' => 'Isolant.jpg352a5ad013d0d38b8da0c120d8c78c145eddfe7a.jpg',
                        ],
                      15 =>
                        [
                        'name' => 'Slope insulation',
                        'image' => 'Isolant_pente.jpg5dc2099a92c584641d82137c2e660f302be07e0f.jpg',
                        ],
                      16 =>
                        [
                        'name' => 'Expansion joint',
                        'image' => 'Joint_dilatation.JPG8a1fa19b7817a30905eaef0f5aad1c90d7249259.JPG',
                        ],
                      17 =>
                        [
                        'name' => 'Mastic',
                        'image' => 'Mastics.jpg219e1de341f9e35780e4c49a46e5211ffce5a8a0.jpg',
                        ],
                      18 =>
                        [
                        'name' => 'Capillary mat',
                        'image' => 'Matelas_Capillaire.jpg81a5d61fb4095dab652498545baf81161cde5355.jpg',
                        ],
                      19 =>
                        [
                        'name' => 'Waterproofing membrane',
                        'image' => 'Membrane_Étanchéité.jpg8d4a277113f1405e9ecb3b10ad266fb162812e1a.jpg',
                        ],
                      20 =>
                        [
                        'name' => 'Cap sheet membrane',
                        'image' => 'Membrane_Finition.JPG9bd5e2e925e9dc686023bc1f9c96b144560b32b4.JPG',
                        ],
                      21 =>
                        [
                        'name' => 'Flashing membrane',
                        'image' => 'Membrane_Relevé.jpg83db15f892241aa2404bd156c2bb0d19ea113160.jpg',
                        ],
                      22 =>
                        [
                        'name' => 'Transition membrane',
                        'image' => 'Membrane_Transition.jpgd5cba2b9ee2800b2063c267142fbbc077151e36a.jpg',
                        ],
                      23 =>
                        [
                        'name' => 'Base sheet membrane',
                        'image' => 'Membrane_Sous-Couche.JPGaa449ef46e158240e2ddff384742b02ef3a0cc23.JPG',
                        ],
                      24 =>
                        [
                        'name' => 'Roofing underlayment',
                        'image' => 'Membrane_Sous-Toiture.JPG1c2b966a32e1c8c741c044ef791c6dc886505ba3.JPG',
                        ],
                      25 =>
                        [
                        'name' => 'Through wall membrane',
                        'image' => 'Membrane_intramurale.jpg5d3b944a589c4d961c5c0f4395ea78db54821a3f.jpg',
                        ],
                      26 =>
                        [
                        'name' => 'Liquid membrane',
                        'image' => 'Membrane_Liquide.jpgaf216aba517477b1643c5466432c8281b032c6ab.jpg',
                        ],
                      27 =>
                        [
                        'name' => 'Composite board',
                        'image' => 'Panneau_composite.jpg48e165e49271857ee2ef2c38d118d4f26da91fbe.jpg',
                        ],
                      28 =>
                        [
                        'name' => 'Drainage board',
                        'image' => 'Panneau_Drainage.jpgc7d94aa905b034cba8f21317cc760be09563053f.jpg',
                        ],
                      29 =>
                        [
                        'name' => 'Overlay board',
                        'image' => 'Panneau_recouvrement.jpgdfe5e744a2ffeeaf57d3abc86e32c02414970721.jpg',
                        ],
                      30 =>
                        [
                        'name' => 'Recovering board',
                        'image' => 'Panneau_Réfection.jpg9c88ae7859bb5adc4e6645dcca8008549a9fb584.jpg',
                        ],
                      31 =>
                        [
                        'name' => 'Support panel',
                        'image' => 'Panneau_Support.jpg4e1835ab749d636686e2965090184e8abfe51bc5.jpg',
                        ],
                      32 =>
                        [
                        'name' => 'Insulation board',
                        'image' => 'Panneau_isolant.JPGdfee7f91cd5b8ebf46c4b7883ea56b903260f580.JPG',
                        ],
                      33 =>
                        [
                        'name' => 'Air barrier',
                        'image' => 'Pare-air.JPGbf8bb98dfc8b729c12fb50f0532dfe183eae39f1.JPG',
                        ],
                      34 =>
                        [
                        'name' => 'Vapour barrier',
                        'image' => 'Pare-air.JPG8248642e4e1f34e33f55c770c55161d6a798e252.JPG',
                        ],
                      35 =>
                        [
                        'name' => 'Waterproof paste',
                        'image' => 'Pâte_étanchéité.jpg3de80e3d985060a0a7372b714110f9ff7921aa2e.jpg',
                        ],
                      37 =>
                        [
                        'name' => 'Sealant',
                        'image' => 'Scellant.jpg5fd2d2279e1abeaf56cb482dc255f52fb403dac0.jpg',
                        ],
                      38 =>
                        [
                        'name' => 'Growing medium',
                        'image' => 'Substrat_Croissance.jpg89a4aee800ddfdc66daf3b00fd426fffc575fee9.jpg',
                        ],
                      39 =>
                        [
                        'name' => 'Vapour permeable',
                        'image' => null,
                        ],
                        ],
                    ],
                ],
            ],
            [
                'label' => [
                  'name'   => 'child_product_name',
                  'values' =>
                   [
                    'en' => 'Product name',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 10,
                  'name'                => 'child_product_name',
                  'model_type'          => 'child_product',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'name',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
                'natures' => [1, 2, 3, 4],
            ],
            [
                'label' => [
                  'name'   => 'child_product_code',
                  'values' =>
                   [
                    'en' => 'Product code',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 24,
                  'name'                => 'child_product_code',
                  'model_type'          => 'child_product',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
                'natures' => [1, 2, 3, 4],
            ],
            [
                'label' => [
                  'name'   => 'spec_name',
                  'values' =>
                   [
                    'en' => 'Specification name',
                   ],
                ],

                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 10,
                  'name'                => 'spec_name',
                  'model_type'          => 'specification',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'name',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
            ],
            [
                'label' => [
                  'name'   => 'spec_sheet',
                  'values' =>
                   [
                    'en' => 'Specification',
                   ],
                ],

                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 7,
                  'name'                => 'spec_sheet',
                  'model_type'          => 'specification',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'file',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
            ],
            [
                'label' => [
                  'name'   => 'detail_name',
                  'values' =>
                   [
                    'en' => 'Detail name',
                   ],
                ],

                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 10,
                  'name'                => 'detail_name',
                  'model_type'          => 'detail',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'name',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
            ],
            [
                'label' => [
                  'name'   => 'product_technical_sheet',
                  'values' =>
                   [
                    'en' => 'Details sheet',
                   ],
                ],

                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 8,
                  'name'                => 'detail_sheet',
                  'model_type'          => 'detail',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'file',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
            ],
            [
                'label' => [
                  'name'   => 'technical_bulletin_name',
                  'values' =>
                   [
                    'en' => 'Technical bulletin name',
                   ],
                ],

                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 10,
                  'name'                => 'technical_bulletin_name',
                  'model_type'          => 'technical_bulletin',

                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'name',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
            ],
            [
                'label' => [
                  'name'   => 'product_function',
                  'values' =>
                   [
                    'en' => 'Function',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 4,
                  'name'                => 'product_function',
                  'model_type'          => 'parent_product',
                  'has_value'           => true,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => true,
                  'options'             =>
                   [
                    'special_index_key' => 'list_function',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => true,
                ],
                'natures' => [1, 2, 3, 4],
                'value' =>  [
                  'attribute_id' => 3,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 =>
                       [
                        'name' => 'Waterproofing',
                        'image' => 'Étanchéité.png1c5cf12b4a718e1f536ef790c63e6a15ac49953e.png',
                       ],
                      1 =>
                       [
                        'name' => 'Insulation',
                        'image' => 'Isolation.pnga0b6a26cbdfae7dff64b7602c5470f13be5ec4ed.png',
                       ],
                      2 =>
                       [
                        'name' => 'Soundproofing',
                        'image' => 'Insonorisation.pngf8c54add98eb88112cb77a1b6432daa58881465c.png',
                       ],
                      3 =>
                       [
                        'name' => 'Vegetative solutions',
                        'image' => 'Végétalisation.png906c6609b7b68389f4f5ad12999aa47e2c44eccd.png',
                       ],
                      4 =>
                       [
                        'name' => 'Accessory products',
                        'image' => 'ComplementaryProducts.png602f05433279a71d452d2b9d384b25fead36a9eb.png',
                       ],
                     ],
                   ],
                ],
            ],
            [
                'label' => [
                  'name'   => 'detail_building_component',
                  'values' =>
                   [
                    'en' => 'Building Component',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 3,
                  'name'                => 'detail_building_component',
                  'model_type'          => 'detail',
                  'has_value'           => true,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => true,
                ],
                'value' =>  [
                  'attribute_id' => 57,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 => 'Roofs',
                      1 => 'Foundations',
                      2 => 'Walls',
                      3 => 'Bridges',
                      4 => 'Parking decks',
                      5 => 'Balconies and Plaza decks',
                      6 => 'Fountains and Ponds',
                      7 => 'Indoor Applications',
                     ],
                   ],
                ],
            ],
            [
                'label' => [
                  'name'   => 'detail_function',
                  'values' =>
                   [
                    'en' => 'Function',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 4,
                  'name'                => 'detail_function',
                  'model_type'          => 'detail',
                  'has_value'           => true,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => true,
                  'options'             =>
                   [
                    'special_index_key' => 'list_function',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => true,
                ],
                'value' =>  [
                  'attribute_id' => 122,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 =>
                       [
                        'name' => 'Waterproofing',
                        'image' => 'Étanchéité.png1c5cf12b4a718e1f536ef790c63e6a15ac49953e.png',
                       ],
                      1 =>
                       [
                        'name' => 'Insulation',
                        'image' => 'Isolation.pnga0b6a26cbdfae7dff64b7602c5470f13be5ec4ed.png',
                       ],
                      2 =>
                       [
                        'name' => 'Soundproofing',
                        'image' => 'Insonorisation.pngf8c54add98eb88112cb77a1b6432daa58881465c.png',
                       ],
                      3 =>
                       [
                        'name' => 'Vegetative solutions',
                        'image' => 'Végétalisation.png906c6609b7b68389f4f5ad12999aa47e2c44eccd.png',
                       ],
                      4 =>
                       [
                        'name' => 'Accessory products',
                        'image' => 'ComplementaryProducts.png602f05433279a71d452d2b9d384b25fead36a9eb.png',
                       ],
                     ],
                   ],
                ],
            ],
            [
                'label' => [
                  'name'   => 'technical_bulletin_file',
                  'values' =>
                   [
                    'en' => 'Technical bulletin files',
                   ],
                ],

                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 8,
                  'name'                => 'technical_bulletin_file',
                  'model_type'          => 'technical_bulletin',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'file',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
            ],
            [
                'label' => [
                  'name'   => 'system_name',
                  'values' =>
                   [
                    'en' => 'System name',
                   ],
                ],

                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 10,
                  'name'                => 'system_name',
                  'model_type'          => 'system',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'name',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
            ],
            [
                'label' => [
                  'name'   => 'system_3d_model',
                  'values' =>
                   [
                    'en' => '3D model',
                   ],
                ],

                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 9,
                  'name'                => 'system_3d_model',
                  'model_type'          => 'system',
                  'has_value'           => false,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                    'special_index_key' => 'image_3d',
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => false,
                ],
            ],
            [
                'label' => [
                  'name'   => 'spec_building_component',
                  'values' =>
                   [
                    'en' => 'Building Component',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 3,
                  'name'                => 'spec_building_component',
                  'model_type'          => 'specification',
                  'has_value'           => true,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => true,
                ],
                'value' =>  [
                  'attribute_id' => 50,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 => 'Roofs',
                      1 => 'Foundations',
                      2 => 'Walls',
                      3 => 'Bridges',
                      4 => 'Parking decks',
                      5 => 'Balconies and Plaza decks',
                      6 => 'Fountains and Ponds',
                      7 => 'Indoor Applications',
                     ],
                   ],
                ],
            ],
            [
                'label' => [
                  'name'   => 'technical_bulletin_building_component',
                  'values' =>
                   [
                    'en' => 'Building Component',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 3,
                  'name'                => 'technical_bulletin_building_component',
                  'model_type'          => 'technical_bulletin',
                  'has_value'           => true,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                   [
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => true,
                ],
                'value' =>  [
                  'attribute_id' => 63,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 => 'Roofs',
                      1 => 'Foundations',
                      2 => 'Walls',
                      3 => 'Bridges',
                      4 => 'Parking decks',
                      5 => 'Balconies and Plaza decks',
                      6 => 'Fountains and Ponds',
                      7 => 'Indoor Applications',
                     ],
                   ],
                ],
            ],
            [
                'label' => [
                  'name'   => 'building_component',
                  'values' =>
                   [
                    'en' => 'Building Component',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 3,
                  'name'                => 'building_component',
                  'model_type'          => 'parent_product',
                  'has_value'           => true,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => true,
                  'options'             =>
                   [
                    'special_index_key' => 'list_composante',
                    'not_deletable'     => true,
                   ],
                  'attribute_group_id' => null,
                  'should_index'       => true,
                ],
                'natures' => [1, 2, 3, 4],
                'value' =>  [
                  'attribute_id' => 4,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 => 'Roofs',
                      1 => 'Foundations',
                      2 => 'Walls',
                      3 => 'Bridges',
                      4 => 'Parking decks',
                      5 => 'Balconies and plaza decks',
                      6 => 'Fountains and ponds',
                      7 => 'Indoor applications',
                     ],
                   ],
                ],
            ],
            [
                'label' => [
                  'name'   => 'system_function',
                  'values' =>
                   [
                    'en' => 'Function',
                   ],
                ],
                'attribute' => [
                  'company_id'          => 1,
                  'attribute_type_id'   => 4,
                  'name'                => 'system_function',
                  'model_type'          => 'system',
                  'has_value'           => true,
                  'is_min_requirement'  => true,
                  'is_parent_attribute' => false,
                  'options'             =>
                  [
                      'special_index_key' => 'list_function',
                      'not_deletable'     => true,
                  ],
                  'attribute_group_id' => null,
                  'should_index'       => true,
                ],
                'value' =>  [
                  'attribute_id' => 126,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 =>
                       [
                        'name' => 'Waterproofing',
                        'image' => null,
                       ],
                      1 =>
                       [
                        'name' => 'Insulation',
                        'image' => null,
                       ],
                      2 =>
                       [
                        'name' => 'Soundproofing',
                        'image' => null,
                       ],
                      3 =>
                       [
                        'name' => 'Vegetative solutions',
                        'image' => null,
                       ],
                      4 =>
                       [
                        'name' => 'Accessory products',
                        'image' => null,
                       ],
                     ],
                   ],
                ],
            ],
            [
                'label' => [
                  'name'   => 'system_bridging',
                  'values' =>
                   [
                    'en' => 'Bridging',
                   ],
                ],
                'attribute' => [
                    'company_id'          => 1,
                    'attribute_type_id'   => 3,
                    'name'                => 'system_bridging',
                    'model_type'          => 'system',
                    'has_value'           => true,
                    'is_min_requirement'  => false,
                    'is_parent_attribute' => false,
                    'options'             =>
                   [
                   ],
                    'attribute_group_id'  => null,
                    'should_index'        => true,
                ],
                'value' =>  [
                  'attribute_id' => 124,
                  'values' =>
                   [
                    'en' =>
                     [
                      0 => 'Steel deck',
                      1 => 'Concrete deck',
                      2 => 'Shotcrete',
                      3 => 'Concrete masonary unit',
                      4 => 'Wood deck (plywood)',
                      5 => 'Wood deck (plank)',
                      6 => 'Wood(treated)',
                      7 => 'Wood lagging',
                      8 => 'Insulated concrete form',
                      9 => 'Steel stud',
                      10 => 'Wood stud',
                      11 => 'Work slab',
                      12 => 'Exterior gypsum',
                      13 => 'Stone',
                      14 => 'Prepared soil',
                     ],
                   ],
                ],
            ],
        ];

        return $attributes;
    }
}
