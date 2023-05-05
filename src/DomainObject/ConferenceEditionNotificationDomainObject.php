<?php

namespace App\DomainObject;

use App\Entity\ConferenceEdition;

class ConferenceEditionNotificationDomainObject
{
    public ?string $email = null;
    public ?ConferenceEdition $conferenceEdition = null;
}
