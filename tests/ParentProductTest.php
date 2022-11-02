<?php

use Pimeo\Indexer\CompanyIndexer;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\User;
use Tests\Libs\DatabaseSetup;
use Tests\TestCase;

class ParentProductTest extends TestCase
{
    use DatabaseSetup;

    /** @before */
    protected function before()
    {
        $this->setupTestDatabase();
        app(CompanyIndexer::class)->indexCompanies();
    }

    public function createParentProductValid()
    {
        $this->visit('/parent-product/create')
             ->type('', 'attributes[1][fr]')
             ->type('Product name fr', 'attributes[1][fr]')
             ->type('Product name en', 'attributes[1][en]')
             ->select(1, 'attributes[2]')
             ->select(1, 'attributes[3][]')
             ->select([1, 2], 'attributes[4][]')
             ->select(1, 'attributes[5][]')
             ->press('Save and move to drafts')
        ;
    }

    public function testHomePage()
    {
        $this->visit('/')
             ->see('Welcome home!');
    }

    public function testCreateParentProductShowErrors()
    {
        $this->visit('/parent-product/create')
             ->press('Save and move to drafts')
        ;

        $this->seePageIs('/parent-product/create')
             ->see('This field is required.')
        ;
    }

    public function testCreateParentProductFillFieldsOnErrors()
    {
        $this->visit('/parent-product/create')
             ->type('', 'attributes[1][fr]')
             ->type('Product name en', 'attributes[1][en]')
             ->select(1, 'attributes[2]')
             ->select(1, 'attributes[3][]')
             ->select([1, 2], 'attributes[4][]')
             ->select(1, 'attributes[5][]')
        ;
        $this->press('Save and move to drafts');

        $this->seePageIs('/parent-product/create')
             ->see('This field is required.')
             ->seeInField('attributes[1][en]', 'Product name en')
             ->seeIsSelected('attributes[2]', 1)
             ->seeIsSelected('attributes[3][]', 1)
             ->seeIsSelected('attributes[4][]', 1)
             ->seeIsSelected('attributes[4][]', 2)
             ->seeIsSelected('attributes[5][]', 1)
        ;
    }

    public function testCreateParentProductValid()
    {
        $this->createParentProductValid();

        $this->seePageIs('/parent-product/listing')
             ->see('The parent product has been created.')
             ->visit('/parent-product/listing/draft')
             ->see('Product name en')
             ->see('Sheet')
             ->see('Fondations, Murs')
        ;

        $this->seeInDatabase(
            'parent_products',
            [
                'created_by'  => $this->superAdmin->id,
                'updated_by'  => $this->superAdmin->id,
            ]
        );
    }

    public function testEditParentProductNoChanges()
    {
        $parent = $this->createValidParentProduct($this->admin->getCompany()->languages);

        $this->visit("/parent-product/{$parent->id}/edit");
        $this->press('Save');
        $this->seePageIs("/parent-product/{$parent->id}/edit");
        $this->see('The parent product has been modified.');
    }

    public function testEditParentProductValid()
    {
        $this->actingAs($this->user);

        $parentProduct = $this->createValidParentProduct($this->admin->getCompany()->languages);

        $this->actingAs($this->admin);

        LinkAttribute::whereAttributeId(8)->delete();
        LinkAttribute::whereAttributeId(22)->delete();

        $linkAttribute = new LinkAttribute(['attribute_id' => 22]);
        $field_code = $linkAttribute->attribute->type->code;
        $fieldClass = 'Pimeo\\Forms\\Fields\\' . studly_case($field_code);
        $fieldType = app($fieldClass);

        $fieldValue = $fieldType->formToValues([
            'fr' => [['name' => 'generic.png', 'extension' => 'png', 'file_path' => 'img/generic.png']],
            'en' => [['name' => 'generic.png', 'extension' => 'png', 'file_path' => 'img/generic.png']],
        ]);

        $linkAttribute->values = $fieldValue;
        $parentProduct->linkAttributes()->save($linkAttribute);

        $linkAttribute = new LinkAttribute(['attribute_id' => 8]);

        $linkAttribute->values = $fieldValue;
        $parentProduct->linkAttributes()->save($linkAttribute);

        $parentProduct->mediaLinks()->updateOrCreate(['media_id' => 1]);
        $parentProduct->save();

        $this->visit("/parent-product/{$parentProduct->id}/edit")
             ->select(2, 'attributes[2]')
             ->select(2, 'attributes[3][]')
             ->select([3, 4], 'attributes[4][]')
             ->select(2, 'attributes[5][]')
             ->type('Product name fr2', 'attributes[1][fr]')
             ->type('Product name en2', 'attributes[1][en]')
             ->press('Publish')
        ;

        $parentProduct->update(['status' => 'published']);

        $this->seePageIs("/parent-product/{$parentProduct->id}/edit")
             ->see('The parent product has been modified.')
             ->visit('/parent-product/listing/published')
             ->see('Product name en2')
             ->see('Sheet')
             ->see('Ponts, Stationnements')
        ;

        $this->seeInDatabase('parent_products', [
            'id'         => $parentProduct->id,
            'created_by' => $this->user->id,
            'updated_by' => $this->admin->id,
        ]);
    }
}
