<?php

class EditableFormFieldFreshdeskExtension extends DataExtension
{
    /**
     * @var FreshdeskService
     */
    public $freshdeskService;

    private static $dependencies = [
        'freshdeskService' => '%$FreshdeskService',
    ];

    private static $db = [
        'FreshdeskFieldMapping' => 'Text',
        'FreshdeskFieldCustom' => 'Boolean',
        'FreshdeskForceInt' => 'Boolean',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.Main', new TextField('FreshdeskFieldMapping', 'Freshdesk field mapping:'));
        $fields->addFieldToTab('Root.Main', new CheckboxField('FreshdeskFieldCustom', 'Freshdesk custom field'));
        $fields->addFieldToTab('Root.Main', new CheckboxField('FreshdeskForceInt', 'Force custom field to Integer (eg. Priority):'));
    }

    /*
    * Ensure Freshdesk fields exist via API call
    */
    public function validate(ValidationResult $validationResult)
    {
        if (!$this->owner->FreshdeskFieldMapping) {
            return $validationResult->valid();
        }

        $validFields = $this->freshdeskService->getFieldMappings();

        foreach ($validFields as $field) {
            if ($field['name'] == $this->owner->FreshdeskFieldMapping) {
                return $validationResult->valid();
            }
        }

        return $validationResult->error(sprintf('%s is not a valid Freshdesk field', $this->owner->FreshdeskFieldMapping));
    }
}
