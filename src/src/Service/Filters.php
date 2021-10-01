<?php

namespace App\Service;

class Filters
{
    /**
     * @var string
     */
    private string $site;

    /**
     * @var string
     */
    private string $tagged;

    /**
     * @var string|null
     */
    private ?string $fromDate;

    /**
     * @var string|null
     */
    private ?string $toDate;

    /**
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * @param string $site
     */
    public function setSite(string $site): void
    {
        $this->site = $site;
    }

    /**
     * @return string
     */
    public function getTagged(): string
    {
        return $this->tagged;
    }

    /**
     * @param string $tagged
     */
    public function setTagged(string $tagged): void
    {
        $this->tagged = $tagged;
    }

    /**
     * @return string|null
     */
    public function getFromDate(): ?string
    {
        return $this->fromDate;
    }

    /**
     * @param string|null $fromDate
     */
    public function setFromDate(?string $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return string|null
     */
    public function getToDate(): ?string
    {
        return $this->toDate;
    }

    /**
     * @param string|null $toDate
     */
    public function setToDate(?string $toDate): void
    {
        $this->toDate = $toDate;
    }
}