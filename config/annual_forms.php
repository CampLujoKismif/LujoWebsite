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

By completing this agreement I certify that:

- I am the legal parent/guardian of the camper(s) listed on this account
- The information provided in the registration and medical forms is accurate to the best of my knowledge
- I authorize the release of medical treatment for my camper(s) if necessary
- I agree to review and abide by all camp policies, expectations, and communication updates for the upcoming camp year
- I understand that it is my responsibility to notify camp leadership of any changes to my camper's medical or personal information

I acknowledge that my electronic signature carries the same legal weight as a handwritten signature.
MARKDOWN,
    ],

    'camper_agreement' => [
        'slug' => 'camper-code-of-conduct',
        'title' => 'Camper Code of Conduct',
        'content' => <<<MARKDOWN
### Camper Code of Conduct

I agree to:

- Respect camp staff, volunteers, and other campers
- Follow all instructions given for my safety and the safety of others
- Participate fully in scheduled camp activities
- Refrain from bringing prohibited items or engaging in prohibited behavior
- Inform a staff member immediately if I feel unsafe or witness unsafe behavior

I understand that failure to follow these expectations may result in disciplinary action or dismissal from camp.
MARKDOWN,
    ],
];


