<?php

Class UserDefinedForm_ControllerFreshdeskExtension extends Extension
{
    public function updateEmailData($emailData, $attachments)
    {
        if (!$this->owner->FreshdeskDomain || !$this->owner->ExportToFreshdesk) {
            return false;
        }

        $formattedData = '';
        foreach ($emailData['Fields'] as $field) {
            $formattedData .= "<p><b>".$field->Title.":</b></p>";
            $formattedData .= "<p>".$field->Value."</p>";
            $formattedData .= "<br>";
        }

        $ticketData = [
          "description" => $formattedData,
          "subject" => "[".$this->owner->Title."]",
          "email" => $emailData['Sender']->Email,
          "priority" => 2,
          "status" => 2,
        ];

        $headers = ["Content-type" => "application/json"];
        $freshdesk = new Freshdesk();
        $freshdesk->APICall($ticketData, $headers, 'POST', $this->owner->FreshdeskDomain, '/api/v2/tickets');
    }
}
