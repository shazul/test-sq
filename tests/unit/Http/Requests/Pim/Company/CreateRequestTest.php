<?php

namespace Tests\Unit;

use Pimeo\Http\Requests\Pim\Company\CreateRequest;
use Pimeo\Indexer\CompanyIndexer;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;
use Validator;

class CreateRequestTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    public function before()
    {
        $this->setupTestDatabase();
    }

    public function test_a_company_must_have_a_name_and_languages()
    {
        $data = [
            'name'      => '',
            'languages' => [],
        ];

        $request = new CreateRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());

        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_a_company_must_have_english_language_invalid()
    {
        $data = [
            'name'      => 'test name',
            'languages' => [1],
        ];

        $request = new CreateRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());

        $this->assertArrayHasKey('languages', $validator->errors()->toArray());
    }

    public function test_a_company_must_have_english_language_valid()
    {
        $data = [
            'name'      => 'test name',
            'languages' => [1, 2],
        ];

        $request = new CreateRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes(), $validator->errors());
    }
}
