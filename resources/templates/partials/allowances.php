<?php

/**
 * @var array $allowances
 */

if ($allowances) {
?>
    <ram:SpecifiedTradeAllowanceCharge>
        <?php
        foreach ($invoice->allowances as $allowance) {
        ?>
            <ram:ChargeIndicator>
                <udt:Indicator><?= !$allowance->isAllowance; ?></udt:Indicator>
                <?php
                if ($allowance->percentage) {
                ?>
                    <ram:CalculationPercent><?= $allowance->percentage; ?></ram:CalculationPercent>
                <?php
                }

                if ($allowance->baseAmount) {
                ?>
                    <ram:BasisAmount><?= $allowance->baseAmount; ?></ram:BasisAmount>
                <?php
                }
                ?>
                <ram:ActualAmount><?= $allowance->amount; ?></ram:ActualAmount>

                <?php
                if ($allowance->reasonCode) {
                ?>
                    <ram:ReasonCode><?= $allowance->reasonCode; ?></ram:ReasonCode>
                <?php
                }
                if ($allowance->reason) {
                ?>
                    <ram:Reason><?= $allowance->reason; ?></ram:Reason>
                <?php
                }
                ?>
                <ram:CategoryTradeTax>
                    <ram:TypeCode>VAT</ram:TypeCode>
                    <ram:CategoryCode><?= $allowance->vatCategory->value; ?></ram:CategoryCode>
                    <?php
                    if ($allowance->vatRate) {
                    ?>
                        <ram:RateApplicablePercent><?= $allowance->vatRate; ?></ram:RateApplicablePercent>
                    <?php
                    }
                    ?>
                </ram:CategoryTradeTax>
            </ram:ChargeIndicator>
    </ram:SpecifiedTradeAllowanceCharge>
<?php
        }
    }
?>