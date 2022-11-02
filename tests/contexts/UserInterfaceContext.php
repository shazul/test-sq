<?php

/**
 * Behat context class.
 */
class UserInterfaceContext extends BaseContext
{
    /**
     * @When I create a role with name :arg1 and description :arg2
     * @param string $description
     */
    public function iCreateARoleWithNameAndDescription($name, $description)
    {
        $this->iAmOnHomepage();
        $this->clickLink('Roles');
        $this->clickLink('Create');
        $this->fillField('name', $name);
        $this->fillField('description', $description);
        $this->assertFieldContains('name', $name);
        $this->assertFieldContains('description', $description);
        $this->pressButton('Save');
    }

    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        $this->iAmOnHomepage();
        $this->assertHomepage();
        $this->fillField('email', 'superadmin@libeo.com');
        $this->fillField('password', '1qaz');
        $this->checkOption('remember');
        $this->pressButton('Sign In');
        $this->assertPageAddress('/home');
    }

    /**
     * @When I create a parent product called :product_name
     */
    public function iCreateAParentProductCalled($product_name)
    {
        $this->fields = [
            'attributes[1][fr]' => 'Product name fr',
            'attributes[1][en]' => $product_name,
        ];
        $this->optionFields = [
            'nature_id'       => 2,
            'attributes[3][]' => 1,
            'attributes[4][]' => 1,
            'attributes[4][]' => 1,
        ];

        $this->visit('/parent-product/create');

        $this->fillFieldsArray();
        $this->additionallySelectOption('attributes[4][]', 2);

        $this->pressButton('Save and move to drafts');
    }

    /**
     * @Then I should see the created product in the parent product list
     */
    public function iShouldSeeTheCreatedProductInTheParentProductList()
    {
        $this->visit('/parent-product/listing/draft');
        $this->assertPageContainsText('AAAAAAAAAAAAA');
        $this->assertPageContainsText('Sheet');
        $this->assertPageContainsText('Foundations, Walls');
    }

    /**
     * @Then I should see the message :message
     */
    public function iShouldSeeTheMessage($message)
    {
        $this->assertPageContainsText($message);
    }

    /**
     * @Then the form should contain the old values
     */
    public function theFormShouldContainTheOldValues()
    {
        foreach ($this->fields as $field => $value) {
            $this->assertFieldContains($field, $value);
        }
        foreach ($this->optionFields as $field => $value) {
            $this->theOptionFromShouldBeSelected($field, $value);
        }
    }
}
