<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Enums;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;

class ProfileTest extends TestCase
{
    public static function isAtLeastMatrix(): array
    {
        return [
            'MINIMUM >= MINIMUM' => [Profile::MINIMUM, Profile::MINIMUM, true],
            'MINIMUM >= BASIC_WL' => [Profile::MINIMUM, Profile::BASIC_WL, false],
            'MINIMUM >= BASIC' => [Profile::MINIMUM, Profile::BASIC, false],
            'MINIMUM >= EN16931' => [Profile::MINIMUM, Profile::EN16931, false],
            'MINIMUM >= EXTENDED' => [Profile::MINIMUM, Profile::EXTENDED, false],
            'MINIMUM >= EXTENDED_CTC_FR' => [Profile::MINIMUM, Profile::EXTENDED_CTC_FR, false],
            'BASIC_WL >= MINIMUM' => [Profile::BASIC_WL, Profile::MINIMUM, true],
            'BASIC_WL >= BASIC_WL' => [Profile::BASIC_WL, Profile::BASIC_WL, true],
            'BASIC_WL >= BASIC' => [Profile::BASIC_WL, Profile::BASIC, false],
            'BASIC_WL >= EN16931' => [Profile::BASIC_WL, Profile::EN16931, false],
            'BASIC_WL >= EXTENDED' => [Profile::BASIC_WL, Profile::EXTENDED, false],
            'BASIC_WL >= EXTENDED_CTC_FR' => [Profile::BASIC_WL, Profile::EXTENDED_CTC_FR, false],
            'BASIC >= MINIMUM' => [Profile::BASIC, Profile::MINIMUM, true],
            'BASIC >= BASIC_WL' => [Profile::BASIC, Profile::BASIC_WL, true],
            'BASIC >= BASIC' => [Profile::BASIC, Profile::BASIC, true],
            'BASIC >= EN16931' => [Profile::BASIC, Profile::EN16931, false],
            'BASIC >= EXTENDED' => [Profile::BASIC, Profile::EXTENDED, false],
            'BASIC >= EXTENDED_CTC_FR' => [Profile::BASIC, Profile::EXTENDED_CTC_FR, false],
            'EN16931 >= MINIMUM' => [Profile::EN16931, Profile::MINIMUM, true],
            'EN16931 >= BASIC_WL' => [Profile::EN16931, Profile::BASIC_WL, true],
            'EN16931 >= BASIC' => [Profile::EN16931, Profile::BASIC, true],
            'EN16931 >= EN16931' => [Profile::EN16931, Profile::EN16931, true],
            'EN16931 >= EXTENDED' => [Profile::EN16931, Profile::EXTENDED, false],
            'EN16931 >= EXTENDED_CTC_FR' => [Profile::EN16931, Profile::EXTENDED_CTC_FR, false],
            'EXTENDED >= MINIMUM' => [Profile::EXTENDED, Profile::MINIMUM, true],
            'EXTENDED >= BASIC_WL' => [Profile::EXTENDED, Profile::BASIC_WL, true],
            'EXTENDED >= BASIC' => [Profile::EXTENDED, Profile::BASIC, true],
            'EXTENDED >= EN16931' => [Profile::EXTENDED, Profile::EN16931, true],
            'EXTENDED >= EXTENDED' => [Profile::EXTENDED, Profile::EXTENDED, true],
            'EXTENDED >= EXTENDED_CTC_FR' => [Profile::EXTENDED, Profile::EXTENDED_CTC_FR, true],
            'EXTENDED_CTC_FR >= MINIMUM' => [Profile::EXTENDED_CTC_FR, Profile::MINIMUM, true],
            'EXTENDED_CTC_FR >= BASIC_WL' => [Profile::EXTENDED_CTC_FR, Profile::BASIC_WL, true],
            'EXTENDED_CTC_FR >= BASIC' => [Profile::EXTENDED_CTC_FR, Profile::BASIC, true],
            'EXTENDED_CTC_FR >= EN16931' => [Profile::EXTENDED_CTC_FR, Profile::EN16931, true],
            'EXTENDED_CTC_FR >= EXTENDED' => [Profile::EXTENDED_CTC_FR, Profile::EXTENDED, true],
            'EXTENDED_CTC_FR >= EXTENDED_CTC_FR' => [Profile::EXTENDED_CTC_FR, Profile::EXTENDED_CTC_FR, true],
        ];
    }

    #[DataProvider('isAtLeastMatrix')]
    public function test_is_at_least_follows_the_profile_hierarchy(
        Profile $current,
        Profile $other,
        bool $expected
    ): void {
        $this->assertSame($expected, $current->isAtLeast($other));
    }

    public function test_extended_ctc_fr_base_profile_is_extended(): void
    {
        $this->assertSame(Profile::EXTENDED, Profile::EXTENDED_CTC_FR->toBaseProfile());
    }

    #[TestWith([Profile::MINIMUM])]
    #[TestWith([Profile::BASIC_WL])]
    #[TestWith([Profile::BASIC])]
    #[TestWith([Profile::EN16931])]
    #[TestWith([Profile::EXTENDED])]
    public function test_base_profile_is_itself_for_every_other_profile(Profile $profile): void
    {
        $this->assertSame($profile, $profile->toBaseProfile());
    }

    #[TestWith([Profile::MINIMUM, 'MINIMUM'])]
    #[TestWith([Profile::BASIC_WL, 'BASIC WL'])]
    #[TestWith([Profile::BASIC, 'BASIC'])]
    #[TestWith([Profile::EN16931, 'EN16931'])]
    #[TestWith([Profile::EXTENDED, 'EXTENDED'])]
    #[TestWith([Profile::EXTENDED_CTC_FR, 'EXTENDED'])]
    public function test_conformance_level_matches_the_xmp_vocabulary(Profile $profile, string $expected): void
    {
        $this->assertSame($expected, $profile->toConformanceLevel());
    }
}
