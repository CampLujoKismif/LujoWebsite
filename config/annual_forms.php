<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Annual Forms Configuration
    |--------------------------------------------------------------------------
    |
    | These definitions are used when generating the annual compliance
    | documents. For now they are hardcoded; eventually these could be moved
    | to a database-driven admin form builder.
    |
    */

    'default_year' => env('ANNUAL_FORMS_YEAR'),

    'information_form_version' => '2025.1',
    'medical_form_version' => '2025.1',

    'parent_agreement' => [
        'slug' => 'parent-guardian-agreement',
        'title' => 'Parent / Guardian Annual Agreement',
        'content' => <<<MARKDOWN
### Parent / Guardian Agreement

As the parent or guardian of the camper named above, I hereby provide my approval for their registration. I understand and agree that camp fees must be paid in advance and are non-refundable except in cases where a camper leaves due to sickness, in which case a refund of one-half of the unused fees may be granted. I acknowledge that the camp operates under a strict non-discrimination policy regarding service to campers based on race, color, or national origin. However, I also understand that the Camp Director reserves the right to reject applications based on the camper's past conduct or dismiss a camper for violating camp rules. While the camp takes reasonable precautions to ensure camper safety, I understand and agree that the camp assumes no responsibility for the camper’s personal property and is released from liability in connection with medical care, medical service, or exposure to any disease-causing organisms, except in cases covered by the camp’s camper insurance. In the event of an emergency, I authorize the camper named in this registration form to receive necessary medical treatment from any hospital, clinic, or healthcare provider. Furthermore, I understand that the camp may use images or recordings containing the camper in promotional materials without compensation, and I release the camp from any liability related to such use. By signing below, I confirm that I have read and understood the terms and conditions outlined herein:

I acknowledge that my electronic signature carries the same legal weight as a handwritten signature.
MARKDOWN,
    ],

    'camper_agreement' => [
        'slug' => 'camper-code-of-conduct',
        'title' => 'Camper Code of Conduct',
        'content' => <<<MARKDOWN
### Camper Code of Conduct

I agree to abide by ALL the rules of the camp as outlined in the session information page and to do my best at all times to be a
true Lu-Jo KISMIF camper, I hereby apply to attend Camp Lu-Jo KISMIF during the camp session indicated above.
MARKDOWN,
    ],
];



