<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Http\Request;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Models\ParentProduct;
use Pimeo\Repositories\Traits\Decodable;
use Pimeo\Repositories\Traits\QueryableFields;
use Symfony\Component\HttpFoundation\Response;

class ExportationController extends Controller
{
    use QueryableFields, Decodable;

    public function index()
    {
        if (current_company()->id != 1) {
            return redirect('home');
        }
        return view('pim.exportation.index');
    }

    public function exportAll(Request $request)
    {

        if (!$request->has('lang') || !$request->has('role')) {
            die;
        }

        $lang = $request->query('lang');
        $role = $request->query('role');

        $products = $this->getAllProducts($lang);
        $products = $this->setComplementaryName($products);
        $products = $this->orderProducts($products, $lang);
        $products = $this->moveAttributesToParentIfTheyAreTheSame($products);

        $filename = "exportation/all_products_$lang.csv";

        if ($role == 'trierProduits') {
            $filename = "exportation/all_products_pour_tri_$lang.csv";
            $products = $this->adjustProductsForSorting($products);
        }

        $this->generateAllProductsCsv($products, $filename, $lang);

        return new Response(file_get_contents($filename), 200, [
            'Content-Description'       => 'File Transfer',
            'Content-Disposition'       => 'attachment; filename="' . $filename . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type'              => 'text/csv',
        ]);
    }

    private function getAllProducts($lang)
    {
        $products = [];

        $elactic_search_server = env('ELASTIC_SEARCH_HOST', 'localhost');
        $elactic_search_port = env('ELASTIC_SEARCH_PORT', '9200');
        $base_url = "http://{$elactic_search_server}:{$elactic_search_port}/_sql?sql=";
        $index = "company-1-$lang/product";
        $query = "SELECT * FROM {$index} LIMIT 5000";

        $response = file_get_contents($base_url . urlencode($query));
        $response = json_decode($response, true)['hits']['hits'];

        if (empty($response)) {
            return $products;
        }

        foreach ($response as $parent_product) {
            $parent_product = $parent_product['_source'];
            $index = $parent_product['parent_id'];
            $products[$index]['name'] = $parent_product['name'];
            $products[$index]['id'] = $parent_product['parent_id'];
            if (!empty($parent_product['complementary'])) {
                $products[$index]['complementary_product'] = $parent_product['complementary'];
            } else {
                $products[$index]['complementary_product'] = '';
            }

            if (!empty($parent_product['attributes'])) {
                foreach ($parent_product['attributes'] as $attribute) {
                    if ($attribute['name'] == 'list_avantages') {
                        $products[$index]['list_avantages1'] =
                            isset($attribute['values'][0]['value']) ? $attribute['values'][0]['value'] : '';

                        $products[$index]['list_avantages2'] =
                            isset($attribute['values'][1]['value']) ? $attribute['values'][1]['value'] : '';

                        $products[$index]['list_avantages3'] =
                            isset($attribute['values'][2]['value']) ? $attribute['values'][2]['value'] : '';
                    } else {
                        $products[$index][$attribute['name']] = $this->getValues($attribute);
                    }
                }
            }
            if (!empty($parent_product['children'])) {
                foreach ($parent_product['children'] as $children) {
                    $index_child = $children['name'] . $children['product_id'];
                    $products[$index]['childs'][$index_child]['name'] = $children['name'];
                    $products[$index]['childs'][$index_child]['id'] = $children['product_id'];

                    foreach ($children['attributes'] as $attribute) {
                        $products[$index]['childs'][$index_child][$attribute['name']] = $this->getValues($attribute);
                    }

                    if (!empty($products[$index]['childs'][$index_child]['child_product_code'])
                        && $products[$index]['childs'][$index_child]['child_product_code'][0] == '0'
                    ) {
                        $products[$index]['childs'][$index_child]['child_product_code'] = '_' .
                            $products[$index]['childs'][$index_child]['child_product_code'];
                    }
                }
                ksort($products[$index]['childs']);
            }
        }
        return $products;
    }

    private function setComplementaryName($products)
    {
        foreach ($products as &$product) {
            if (empty($product['complementary_product'])) {
                continue;
            }
            $complementary_product = [];
            foreach ($product['complementary_product'] as $id_product) {
                if (isset($products[$id_product]['name'])) {
                    $complementary_product[] = $products[$id_product]['name'];
                }
            }
            $product['complementary_product'] = implode(', ', $complementary_product);
        }
        return $products;
    }

    private function getValues($values)
    {
        $formatted_values = [];
        foreach ($values['values'] as $value) {
            if (isset($value['value'])) {
                if (isset($value['converted'])) {
                    $formatted_values[] = $value['value'] . ' ' .
                        (!empty($values['unit_type']) ? $values['unit_type'] : '') .
                        ' (' . $value['converted'] . ')';
                } else {
                    $formatted_values[] = $value['value'] . ' ' .
                        (!empty($values['unit_type']) ? $values['unit_type'] : '');
                }
            } elseif (isset($value['url'])) {
                $formatted_value = $value['url'];

                if (isset($value['image'])) {
                    $formatted_value = "{$value['url']}\n{$value['image']}";
                }

                $formatted_values[] = $formatted_value;
            }
        }
        return implode('+', $formatted_values);
    }

    private function orderProducts($products, $lang)
    {
        usort($products, "self::cmp");
        $products_copy = $products;
        if ($lang == 'fr') {
            $order_composant = [
                'Toits' => [],
                'Murs' => [],
                'Fondations' => [],
                'Ponts' => [],
                'Stationnements' => []
            ];
            $order_roles = ['Membrane de finition' => [], 'Membrane de sous-couche' => [], 'autres' => []];
        } else {
            $order_composant = [
                'Roofs' => [],
                'Walls' => [],
                'Foundations' => [],
                'Bridges' => [],
                'Parking decks' => []
            ];
            $order_roles = ['Cap sheet membrane' => [], 'Base sheet membrane' => [], 'autres' => []];
        }

        foreach ($order_composant as $composant => $v) {
            foreach ($products_copy as $key => $product) {
                if (isset($product['list_composante']) && strpos($product['list_composante'], $composant) !== false) {
                    $order_composant[$composant][] = $product['id'];
                    unset($products_copy[$key]);
                }
            }
        }
        foreach ($products_copy as $product_id => $product) {
            $order_composant['Autres'][] = $product['id'];
        }

        foreach ($products as $key => $product) {
            $found = false;
            foreach ($order_roles as $type => $liste) {
                if (isset($product['list_type']) && strpos($product['list_type'], $type) !== false) {
                    $order_roles[$type][] = $product['id'];
                    $found = true;
                }
            }
            if (!$found) {
                $order_roles['autres'][] = $product['id'];
            }
        }

        $ordered_products = [];
        foreach ($order_composant as $composant) {
            foreach ($order_roles as $role) {
                foreach ($products as $key => $product) {
                    if (in_array($product['id'], $composant) && in_array($product['id'], $role)) {
                        $ordered_products[$key] = $product;
                    }
                }
            }
        }
        return $ordered_products;
    }

    public static function cmp($a, $b)
    {
        return strcmp($a['name'], $b['name']);
    }

    private function moveAttributesToParentIfTheyAreTheSame($products)
    {
        foreach ($products as $key => $product) {
            if (empty($product['childs'])) {
                continue;
            }

            $first_child = reset($product['childs']);
            foreach ($product['childs'] as $child) {
                foreach ($first_child as $attribute_name => $attribute_value) {
                    if (!isset($child[$attribute_name]) || ($child[$attribute_name] != $attribute_value)) {
                        unset($first_child[$attribute_name]);
                    }
                }
            }
            foreach ($first_child as $attribute_name => $attribute_value) {
                if (empty($products[$key][$attribute_name]) || $products[$key][$attribute_name] == $attribute_value) {
                    $products[$key][$attribute_name] = $attribute_value;
                } else {
                    unset($first_child[$attribute_name]);
                }
            }
            foreach ($product['childs'] as $child_id => $child) {
                foreach ($first_child as $attribute_name => $attribute_value) {
                    unset($products[$key]['childs'][$child_id][$attribute_name]);
                }
            }
        }
        return $products;
    }

    private function adjustProductsForSorting($products)
    {
        foreach ($products as $indexParentProduct => $parentProduct) {
            if (empty($parentProduct['childs'])) {
                continue;
            }
            foreach ($parentProduct['childs'] as $indexChild => $childProduct) {
                $products[$indexParentProduct]['childs'][$indexChild]['list_composante'] =
                    $products[$indexParentProduct]['list_composante'];

                $products[$indexParentProduct]['childs'][$indexChild]['list_type'] =
                    $products[$indexParentProduct]['list_type'];

                $products[$indexParentProduct]['childs'][$indexChild]['list_function'] =
                    $products[$indexParentProduct]['list_function'];
            }
        }
        return $products;
    }

    private function generateAllProductsCsv($products, $file_name, $lang)
    {
        $out = fopen($file_name, 'w');
        $csv_fields = $this->getCsvFields();
        $line_template = $this->getLineTemplate($csv_fields);
        $header_line = $this->getHeaderAllProducts($csv_fields, $lang);

        foreach ($products as $parent_product) {
            fputcsv($out, $header_line);

            $line = $line_template;
            foreach ($parent_product as $attribute_name => $attribute_value) {
                if ($attribute_name == 'childs' || !isset($line[$attribute_name])) {
                    continue;
                }
                $line[$attribute_name] = $attribute_value;
            }
            fputcsv($out, $line);

            foreach ($parent_product['childs'] as $child) {
                $line = $line_template;
                $line['parent_product'] = $parent_product['name'];
                foreach ($child as $attribute_name => $attribute_value) {
                    if (isset($line[$attribute_name])) {
                        $line[$attribute_name] = $attribute_value;
                    }
                }
                fputcsv($out, $line);
            }
        }
        fclose($out);
    }

    private function getCsvFields()
    {
        // @codingStandardsIgnoreStart
        return [
            'parent_product'                 => ['fr' => 'Produit parent', 'en' => 'Parent product'],
            'name'                           => ['fr' => 'Nom', 'en' => 'Name'],
            'child_product_code'             => ['fr' => 'Code produit', 'en' => 'Product code'],
            'file_image'                     => ['fr' => 'Photo du produit', 'en' => 'Photo of the product'], // product_image
            'list_composante'                => ['fr' => 'Composante du bâtiment', 'en' => 'Building Component'], // building_component
            'list_function'                  => ['fr' => 'Fonction', 'en' => 'Function'], // product_function
            'list_type'                      => ['fr' => 'Rôle du produit', 'en' => 'Product roles'], // product_role
            'text_description'               => ['fr' => 'Description commerciale', 'en' => 'Commercial description'], // product_description
            'list_avantages1'                => ['fr' => 'Avantage1', 'en' => 'Benefits1'], // product_benefits
            'list_avantages2'                => ['fr' => 'Avantage2', 'en' => 'Benefits2'], // product_benefits
            'list_avantages3'                => ['fr' => 'Avantage3', 'en' => 'Benefits3'], // product_benefits
            'application-long-description-0' => ['fr' => 'Mise oeuvre', 'en' => 'Installation method'],
            'list_certifications' => [
                'fr' => 'Test, Norme et Approbation',
                'en' => 'Test, Standard and Approbation'
            ], // product_test_norm_approbations
            'complementary_product' => ['fr' => 'Produits complémentaires', 'en' => 'Complementary products'],
            'product_installation_video' => ['fr' => 'Vidéo d\'installation', 'en' => 'Installation video'],
            'product_promotional_video' => ['fr' => 'Vidéo promotionnel', 'en' => 'Promotional Video'],
            'product_foundation_type' => ['fr' => 'Type de fondation', 'en' => 'Foundation type'],
            'product_signature' => ['fr' => 'Signature', 'en' => 'Signature'],
            'child_product_surface' => ['fr' => 'Surface', 'en' => 'Surface'],
            'child_product_under_face' => ['fr' => 'Sous-face', 'en' => 'Under face'],
            'product_armature' => ['fr' => 'Armature', 'en' => 'Armature'],
            'text_nature' => ['fr' => 'Nature du produit', 'en' => 'Product nature'], // product_nature
            'product_technology' => ['fr' => 'Technologie', 'en' => 'Technology'],
            'application-0' => ['fr' => 'Mise en oeuvre', 'en' => 'Application'],
            'dimensions-0' => ['fr' => 'Dimensions', 'en' => 'Dimensions'],
            'child_product_length' => ['fr' => 'Longueur', 'en' => 'Length'],
            'width-mmpo-0' => ['fr' => 'Largeur', 'en' => 'Width'],
            'child_product_width' => ['fr' => 'Largeur', 'en' => 'Width'],
            'child_product_thickness' => ['fr' => 'Épaisseur', 'en' => 'Thickness'],
            'board-thickness-0' => ['fr' => 'Épaisseur du panneau', 'en' => 'Board thickness'],
            'weight-0' => ['fr' => 'Poids', 'en' => 'Weight'],
            'child_product_volume' => ['fr' => 'Volume', 'en' => 'Volume'],
            'selvedge-width-0' => ['fr' => 'Largeur du galon', 'en' => 'Selvedge width'],
            'child_product_gross_area' => ['fr' => 'Superficie brute', 'en' => 'Gross area'],
            'child_product_net_area' => ['fr' => 'Superficie nette', 'en' => 'Net area'],
            'child_product_color' => ['fr' => 'Couleur', 'en' => 'Color'],
            'r-value-0' => ['fr' => 'Valeur R', 'en' => 'R Value'],
            'product_COV_content' => ['fr' => 'Teneur en COV', 'en' => 'COV content'],
            'application-temperature-0' => ['fr' => 'Température d\'application', 'en' => 'Application temperature'],
            'child_product_application_temperature' => [
                'fr' => 'Température d\'application',
                'en' => 'Application temperature'
            ],
            'child_product_service_temperature' => ['fr' => 'Température de service', 'en' => 'Service temperature'],
            'child_product_temperature_grade' => ['fr' => 'Grade température', 'en' => 'Grade temperature'],
            'container-1' => ['fr' => 'Contenant', 'en' => 'Container'],
            'coverage-per-pale-0' => ['fr' => 'Pouvoir couvrant par contenant', 'en' => 'Coverage per pail'],
            'pot-life-0' => ['fr' => 'Durée de vie en pot', 'en' => 'Pot life'],
            'product_feature' => ['fr' => 'Caratéristique', 'en' => 'Feature'],
            'product_waterproofing' => ['fr' => 'Perméabilité', 'en' => 'Waterproofing'],
            'limitations-0' => ['fr' => 'Limitations', 'en' => 'Limitations'], // 103
            'child_product_consumption' => ['fr' => 'Consommation', 'en' => 'Consumption'],
            'child_product_fire_grade' => ['fr' => 'Grade feu', 'en' => 'Fire grade'],
            'product_cleaning' => ['fr' => 'Nettoyage', 'en' => 'Cleaning'],
            'product_storage' => ['fr' => 'Entreposage', 'en' => 'Storage'],
            'storage-instructions-0' => ['fr' => 'Instructions d\'entreposage', 'en' => 'Storage instructions'],
            'child_product_number_per_pallet' => ['fr' => 'Nombre par palette', 'en' => 'Number per pallet'],
            'quantity-in-packaging-0' => ['fr' => 'Quantité par emballage', 'en' => 'Quantity in packaging'],
//            'product_qr_code' => ['fr' => 'Code QR', 'en' => 'QR code'],
//            'product_technical_bulletin' => ['fr' => 'Bulletin technique', 'en' => 'Technical Bulletin'],
//            'product_leed' => ['fr' => 'Fiche LEED', 'en' => 'Leed file'],
//            'csa-a12321-14-data-sheet-0' => ['fr' => 'Nom', 'en' => 'Name'],
//            'installation-guide-0' => ['fr' => 'Nom', 'en' => 'Name'],
//            'informational-flyer-0' => ['fr' => 'Nom', 'en' => 'Name'],
//            'service-life-storage-0' => ['fr' => 'Nom', 'en' => 'Name'],
//            'leed-sheet-chilliwack-0' => ['fr' => 'Nom', 'en' => 'Name'],
//            'product_technical_sheet' => ['fr' => 'Fiche de détails', 'en' => 'Details sheet'],
//            'product_product_file' => ['fr' => 'Fichier produit', 'en' => 'Product file'],
//            'product_material_safety_data_sheet' => ['fr' => 'Fiche signalétique', 'en' => 'Material safety data sheet'],
            'promotion' => ['fr' => 'Promotion', 'en' => 'Promotion'],
        ];
        // @codingStandardsIgnoreEnd
    }

    private function getLineTemplate($csv_fields)
    {
        $line_template = [];
        foreach ($csv_fields as $index => $value) {
            $line_template[$index] = '';
        }
        return $line_template;
    }

    private function getHeaderAllProducts($csv_fields, $lang)
    {
        $header = [];
        foreach ($csv_fields as $field) {
            $header[] = $field[$lang];
        }
        return $header;
    }

    public function exportParent(Request $request)
    {
        if (!$request->has('lang')) {
            die;
        }

        $lang = $request->query('lang');
        $filename = "exportation/parent_products_$lang.csv";

        $products = $this->getParentProducts();
        $this->generateCsv($products, $filename, $lang == 'fr' ? 'fr' : 'en');

        return new Response(file_get_contents($filename), 200, [
            'Content-Description'       => 'File Transfer',
            'Content-Disposition'       => 'attachment; filename="' . $filename . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type'              => 'text/csv',
        ]);
    }

    private function getParentProducts()
    {
        $productsList = $this->getQueryForFields(
            'parent_products',
            ParentProduct::class,
            ['name', 'product_description', 'product_benefits']
        );
        $productsList->orderBy('parent_products.id', 'asc');
        $productsList->where('parent_products.status', 'published');
        $productsList = $productsList->get();

        $products = [];
        foreach ($productsList as $product) {
            $products[$product->attributable_id][$product->name] = $this->decodeValues($product);
            $products[$product->attributable_id]['media_name'][$product->media_name] = $product->media_name;
        }
        return $products;
    }

    private function generateCsv($products, $file_name, $lang)
    {
        $out = fopen($file_name, 'w');
        fputcsv($out, $this->getCsvHeader());

        foreach ($products as $id => $product) {
            if (!isset($product['media_name']['website']) || $product['media_name']['website'] != 'website') {
                continue;
            }

            $line = [
                $id,
                isset($product['name'][$lang]) ? $product['name'][$lang] : '',
                isset($product['product_description'][$lang]) ? $product['product_description'][$lang] : '',
                isset($product['product_benefits'][$lang][0]) ? $product['product_benefits'][$lang][0] : '',
                isset($product['product_benefits'][$lang][1]) ? $product['product_benefits'][$lang][1] : '',
                isset($product['product_benefits'][$lang][2]) ? $product['product_benefits'][$lang][2] : '',
            ];
            fputcsv($out, $line);
        }
        fclose($out);
    }

    private function getCsvHeader()
    {
        return [
            'Id',
            'Nom',
            'Description',
            'Avantage #1',
            'Avantage #2',
            'Avantage #3',
        ];
    }
}
