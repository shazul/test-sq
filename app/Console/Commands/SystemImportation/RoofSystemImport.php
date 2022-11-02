<?php

namespace Pimeo\Console\Commands\SystemImportation;

use Exception;
use Illuminate\Filesystem\Filesystem;
use League\Flysystem\FileNotFoundException;

class RoofSystemImport extends AbstractSystemImport
{
    /**
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->signature = 'soprema:import:roofSystem {file : CSV containing the systems}';

        $this->description = 'Import and create roof systems';

        $this->header = $this->setHeaderByCsvRowNumber(62);

        parent::__construct();

        $this->files = $files;

        $this->layer_group_names = ['layer_group_1' => 'Surface 1', 'layer_group_2' => 'Surface 2'];

        $this->field_array_key_match = [
            0  => 'system_name',
            5  => 'system_installation_method',
            4  => 'system_' . self::ROOF_SYSTEM . '_type',
            52 => 'system_' . self::ROOF_SYSTEM . '_high_performance',
            8  => 'system_bridging',
            7  => 'system_' . self::ROOF_SYSTEM . '_insulation',
            6  => 'system_' . self::ROOF_SYSTEM . '_roof_slope',
            54 => 'system_guaranty',
            51 => 'system_complementary_product',
        ];
    }

    /**
     * @throws Exception
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $this->info('Import roof system');

        $this->init();

        $this->write('Getting system type');

        $this->done();

        $this->info('Import products');

        $this->write('> Opening file');

        $file = $this->openFile($this->argument('file'));

        $this->done();

        $this->write('> Reading file');

        $data = $this->readFile($file);

        $this->done();

        $this->write('> Creating Systems');

        $this->start($data);

        $this->done();
    }

    /**
     * @param array $raw_csv_data
     * @return array
     */
    protected function filterRawCsv(array $raw_csv_data)
    {
        $benefits = range(1, 3);
        $group_1_range = range(9, 38);
        $group_2_range = range(39, 50);
        $tests = range(55, 59);
        $install_video = range(60, 61);

        //mise en vedette
        $is_starred = [53];

        $special_keys = array_merge($benefits, $group_1_range, $group_2_range, $tests, $install_video, $is_starred);

        $filtered_data = [];

        foreach ($raw_csv_data as $data_row) {
            $filtered_row = [];

            foreach ($data_row as $key => $data) {
                //filter out special keys
                if (!in_array($key, $special_keys)) {
                    $filtered_row[$this->field_array_key_match[$key]] = $data;
                }
            }

            $group1 = $this->getProductsInGroup($this->buildValuesBasedOnRange($data_row, $group_1_range));
            $group2 = $this->getProductsInGroup($this->buildValuesBasedOnRange($data_row, $group_2_range));

            $benefits_data = $this->buildValuesBasedOnRange($data_row, $benefits);
            if (!empty($benefits_data)) {
                $filtered_row['system_benefit'] = $benefits_data;
            }

            if (!empty($group1)) {
                $filtered_row['layer_group_1'] = $group1;
            }

            if (!empty($group2)) {
                $filtered_row['layer_group_2'] = $group2;
            }

            $test_norms_data = $this->buildValuesBasedOnRange($data_row, $tests);
            if (!empty($test_norms_data)) {
                $filtered_row['system_test_norms_approbations'] = $test_norms_data;
            }

            $video = $this->buildValuesBasedOnRange($data_row, $install_video);
            if (!empty($video)) {
                $filtered_row['system_installation_video'] = $video;
            }
            $filtered_row['is_starred'] = $data_row[53];

            $filtered_row = $this->stripEmptyKeys($filtered_row);
            $filtered_data[] = $filtered_row;
        }

        return $filtered_data;
    }
}
