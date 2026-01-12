<?php

namespace App\Listeners;

use LightSaml\ClaimTypes;
use LightSaml\Model\Assertion\Attribute;

class SamlAssertionAttributes
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // TODO: convert to user based attributes
        $event->attribute_statement
            ->addAttribute(new Attribute('vwjobtitle', 'DLR-OTHER'))
            ->addAttribute(new Attribute('vwdealerCodes', '426A10'))
            ->addAttribute(new Attribute('vwprimarybrand', 'AU'))
            ->addAttribute(new Attribute('mail', 'tnicklas@cantongroup.com'))
            ->addAttribute(new Attribute('cn', 'Talbot Nicklas'))
            ->addAttribute(new Attribute('sn', 'Nicklas'))
            ->addAttribute(new Attribute('givenName', 'Talbot'))
            ->addAttribute(new Attribute('vwpduid', '144878'))
            ->addAttribute(new Attribute('dealercode', '426A10'))
            ->addAttribute(new Attribute('uid', 'vndrcantontn'))
            ->addAttribute(new Attribute('preferredLanguage', 'en'))
            ->addAttribute(new Attribute('departmentNumber', 'DOTH'))
            ->addAttribute(new Attribute('ldap-applroles', 'Magna_Dealer'))
            ->addAttribute(new Attribute(ClaimTypes::PPID, auth()->user()->id))
            ->addAttribute(new Attribute(ClaimTypes::NAME, auth()->user()->name));
    }
}
