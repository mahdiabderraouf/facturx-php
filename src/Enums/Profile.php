<?php

namespace MahdiAbderraouf\FacturX\Enums;

use MahdiAbderraouf\FacturX\Traits\HasValues;

enum Profile: string
{
    use HasValues;

    /** Keep in order */
    case MINIMUM = 'urn:factur-x.eu:1p0:minimum';
    case BASIC_WL = 'urn:factur-x.eu:1p0:basicwl';
    case BASIC = 'urn:cen.eu:en16931:2017#compliant#urn:factur-x.eu:1p0:basic';
    case EN16931 = 'urn:cen.eu:en16931:2017';
    case EXTENDED = 'urn:cen.eu:en16931:2017#conformant#urn:factur-x.eu:1p0:extended';

    /**
     * Check if the current profile if >= $profile
     */
    public function isAtLeast(Profile $profile): bool
    {
        if (
            $profile === Profile::MINIMUM || // current profile is always at least minimum
            $this === $profile ||
            $this === Profile::EXTENDED // extended is the highest profile
        ) {
            return true;
        }

        $thisIndex = array_search($this, self::cases());
        $profileIndex = array_search($profile, self::cases());

        return $thisIndex >= $profileIndex;
    }

    public function toConformanceLevel(): string
    {
        return match ($this) {
            Profile::BASIC_WL => 'BASIC WL',
            default => $this->name,
        };
    }
}
