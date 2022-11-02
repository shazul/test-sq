Feature: Create a new parent product

    @parent-products
    Scenario: Create invalid parent product
      Given I am logged in
      When I create a parent product called ""
      Then I should be on "/parent-product/create"
        And I should see the message "The form contains an error."
        And I should see "This field is required."
        And the form should contain the old values

    @parent-products
    Scenario: Create valid parent product "AAAAAAAAAAAAA"
      Given I am logged in
      When I create a parent product called "AAAAAAAAAAAAA"
      Then I should be on "/parent-product/listing"
        And I should see the message "The parent product has been created."
        And I should see the created product in the parent product list
