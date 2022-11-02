<?php
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Behat context class.
 */
class BaseContext extends MinkContext implements SnippetAcceptingContext
{
    use BootstrapLaravel;
    /**
     * Take screenshot when step fails.
     *
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep($event)
    {
        if ($event->getTestResult()->hasException()) {
            $this->showLastResponse();
        }
    }

    public function fillFieldsArray()
    {
        foreach ($this->fields as $field => $value) {
            $this->fillField($field, $value);
        }

        foreach ($this->optionFields as $field => $value) {
            $this->selectOption($field, $value);
        }
    }

    /**
     * Checks, that option from select with specified id|name|label|value is selected.
     *
     * @Then /^the "(?P<option>(?:[^"]|\\")*)" option from "(?P<select>(?:[^"]|\\")*)" (?:is|should be) selected/
     * @Then /^the option "(?P<option>(?:[^"]|\\")*)" from "(?P<select>(?:[^"]|\\")*)" (?:is|should be) selected$/
     * @Then /^"(?P<option>(?:[^"]|\\")*)" from "(?P<select>(?:[^"]|\\")*)" (?:is|should be) selected$/
     */
    public function theOptionFromShouldBeSelected($select, $option)
    {
        $selectField = $this->getSession()->getPage()->findField($select);
        if (null === $selectField) {
            throw new ElementNotFoundException($this->getSession(), 'select field', 'id|name|label|value', $select);
        }

        $optionField = $selectField->find('named', [
            'option',
            $option,
        ]);

        if (null === $optionField) {
            throw new ElementNotFoundException($this->getSession(), 'select option field', 'id|name|label|value', $option);
        }

        $this->assertBoolean($optionField->isSelected(), 'Select option field with value|text "' . $option . '" is not selected in the select "' . $select . '"');
    }

    /**
     * Asserts a condition.
     *
     * @param bool   $condition
     * @param string $message   Failure message
     *
     * @throws ExpectationException when the condition is not fulfilled
     */
    public function assertBoolean($condition, $message)
    {
        if ($condition) {
            return;
        }

        throw new ExpectationException($message, $this->getSession());
    }
}
