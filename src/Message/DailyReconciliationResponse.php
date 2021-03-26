<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\DailyReconciliationRequestException;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Represents a response to DailyReconciliationRequest
 * @author James
 */
class DailyReconciliationResponse extends AbstractResponse
{

    /**
     * Get the report (raw value)
     * If the report is empty(), the return value will be boolean false
     * Otherwise, an array of values as per https://www.php.net/manual/en/function.str-getcsv.php
     * including the header row as row zero
     */
    public function getReconciliationReport()
    {
        $report = !empty($this->data['reconciliationReport']) ? $this->data['reconciliationReport'] : false;
        return $report;
    }

    /**
     * Return whether the {@link Omnipay\NSWGOVCPP\DailyReconciliationRequest} was successful
     */
    public function isSuccessful() : bool
    {
        return !empty($this->getReconciliationReport());
    }
}
