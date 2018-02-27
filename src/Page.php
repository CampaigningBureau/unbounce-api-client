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
                                        ->setIntegrationsCount(intval($apiData->integrationsCount))
                                        ->setUrl($apiData->url)
                                        ->setDomain($apiData->domain)
                                        ->setSubAccountId($apiData->subAccountId)
                                        ->setVariantsCount(intval($apiData->variantsCount))
                                        ->setIntegrationErrorsCount(intval($apiData->integrationsErrorsCount))
                                        ->setState($apiData->state)
                                        ->setCreatedAt(new Carbon($apiData->createdAt));


        if (property_exists($apiData, 'lastPublishedAt'))
        {
            $page->setLastPublishedAt(new Carbon($apiData->lastPublishedAt));
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     *
     * @return Page
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastPublishedAt()
    {
        return $this->lastPublishedAt;
    }

    /**
     * @param mixed $lastPublishedAt
     *
     * @return Page
     */
    public function setLastPublishedAt($lastPublishedAt)
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