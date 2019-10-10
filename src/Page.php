<?php

namespace CampaigningBureau\UnbounceApiClient;


use Carbon\Carbon;

class Page
{
    /** @var string $id The id of the page */
    private $id;
    /** @var string $name The name of the page */
    private $name;
    /** @var int $integrationsCount How many integrations does the page have */
    private $integrationsCount;
    /** @var string $url Where can the page be called */
    private $url;
    /** @var string $domain The Domain the page is located under */
    private $domain;
    /** @var string $subAccountId The id of the SubAccount the Page belongs to */
    private $subAccountId;
    /** @var int $variantsCount The count of the Variations the page has */
    private $variantsCount;
    /** @var int $integrationErrorsCount The count of the integration errors of this page */
    private $integrationErrorsCount;
    /** @var string $state The state of the page */
    private $state;
    /** @var Carbon $createdAt The timestamp the page was created */
    private $createdAt;
    /** @var Carbon $lastPublishedAt The timestamp the page was last published */
    private $lastPublishedAt;
    /** @var int $formSubmits */
    private $formSubmits;
    /** @var int $visits */
    private $visits;
    /** @var float $conversionRate */
    private $conversionRate;
    /** @var int $clicks */
    private $clicks;
    /** @var int $conversions */
    private $conversions;
    /** @var int $visitors */
    private $visitors;

    /**
     * Page constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function createFromApiData($apiData)
    {
        if (!isset($apiData->id) || empty($apiData->id))
        {
            throw new \InvalidArgumentException("There must be an id given.");
        }

        $page = (new Page($apiData->id))->setName($apiData->name)
                                        ->setIntegrationsCount(intval($apiData->integrations_count))
                                        ->setUrl($apiData->url)
                                        ->setDomain($apiData->domain)
                                        ->setSubAccountId($apiData->sub_account_id)
                                        ->setVariantsCount(intval($apiData->variants_count))
                                        ->setIntegrationErrorsCount(intval($apiData->integrations_errors_count))
                                        ->setState($apiData->state)
                                        ->setCreatedAt(new Carbon($apiData->created_at));


        if (property_exists($apiData, 'last_published_at'))
        {
            $page->setLastPublishedAt(new Carbon($apiData->last_published_at));
        }

        if (property_exists($apiData, 'tests') && property_exists($apiData->tests, 'current')) {
            $page->setFormSubmits(intval($apiData->tests->current->form_submits));
            $page->setVisits(intval($apiData->tests->current->visits));
            $page->setConversionRate(floatval($apiData->tests->current->conversion_rate));
            $page->setClicks(intval($apiData->tests->current->clicks));
            $page->setConversions(intval($apiData->tests->current->conversions));
            $page->setVisitors(intval($apiData->tests->current->visitors));
        }

        return $page;

    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Page
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getIntegrationsCount(): int
    {
        return $this->integrationsCount;
    }

    /**
     * @param int $integrationsCount
     *
     * @return Page
     */
    public function setIntegrationsCount(int $integrationsCount): Page
    {
        $this->integrationsCount = $integrationsCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     *
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     *
     * @return Page
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubAccountId()
    {
        return $this->subAccountId;
    }

    /**
     * @param mixed $subAccountId
     *
     * @return Page
     */
    public function setSubAccountId($subAccountId)
    {
        $this->subAccountId = $subAccountId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVariantsCount()
    {
        return $this->variantsCount;
    }

    /**
     * @param mixed $variantsCount
     *
     * @return Page
     */
    public function setVariantsCount($variantsCount): Page
    {
        $this->variantsCount = $variantsCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getIntegrationErrorsCount(): int
    {
        return $this->integrationErrorsCount;
    }

    /**
     * @param int $integrationErrorsCount
     *
     * @return Page
     */
    public function setIntegrationErrorsCount(int $integrationErrorsCount): Page
    {
        $this->integrationErrorsCount = $integrationErrorsCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return Page
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormSubmits()
    {
        return $this->formSubmits;
    }

    /**
     * @param mixed $formSubmits
     *
     * @return Page
     */
    public function setFormSubmits($formSubmits)
    {
        $this->formSubmits = $formSubmits;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * @param mixed $visits
     *
     * @return Page
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    /**
     * @param mixed $conversionRate
     *
     * @return Page
     */
    public function setConversionRate($conversionRate)
    {
        $this->conversionRate = $conversionRate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * @param mixed $clicconversionRate
     *
     * @return Page
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConversions()
    {
        return $this->conversions;
    }

    /**
     * @param mixed $conversions
     *
     * @return Page
     */
    public function setConversions($conversions)
    {
        $this->conversions = $conversions;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * @param mixed $visitors
     *
     * @return Page
     */
    public function setVisitors($visitors)
    {
        $this->visitors = $visitors;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon $createdAt
     *
     * @return Page
     */
    public function setCreatedAt(Carbon $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getLastPublishedAt(): Carbon
    {
        return $this->lastPublishedAt;
    }

    /**
     * @param Carbon $lastPublishedAt
     *
     * @return Page
     */
    public function setLastPublishedAt(Carbon $lastPublishedAt)
    {
        $this->lastPublishedAt = $lastPublishedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool true, when Page is published
     */
    public function isPublished(): bool
    {
        return $this->state === 'published';
    }
}