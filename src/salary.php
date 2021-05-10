<?php

require '../vendor/autoload.php';

use Carbon\CarbonImmutable;
use League\Csv\Writer;

$carbon = new CarbonImmutable();
CarbonImmutable::setLocale('pl');

$date = $carbon->now();
$year = $date->year;

$csv = Writer::createFromPath(__DIR__ . '/salary.csv', 'w');
$header = ['miesiąc', 'dni wypłaty pensji', 'dni wypłaty premii'];
$csv->insertOne($header);

while ($date->year === $year) {

    $endOfMonth = $date->endOfMonth();
    $bonusDate = $date->day(15)->addMonth();

    $payday = $endOfMonth->isWeekday() ? $endOfMonth : $endOfMonth->previous(CarbonImmutable::FRIDAY);

    $bonusPayday = $bonusDate->isWeekday() ? $bonusDate : $bonusDate->next(CarbonImmutable::WEDNESDAY);

    $record = [$date->monthName, $payday->format('y-m-d'), $bonusPayday->format('y-m-d')];
    $csv->insertOne($record);

    $date = $date->addMonth();
}
