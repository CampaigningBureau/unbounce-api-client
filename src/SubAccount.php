<?php

namespace CampaigningBureau\UnbounceApiClient;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Unbounce;

class SubAccount
{

    const stateUnknown = 'unknown', stateActive = 'active', stateInactive = 'inactive';

    /** @var string $id Id of the Subaccount in Unbounce */
    private $id;

    /** @var string $name The name of the subaccount */
    private $name;

    /** @var string $accountId The id of the account the subaccount belongs to. */
    private $accountId;

    /** @var int $domainsCount The count of domains this subaccount uses */
    private $domainsCount;

    /** @var Carbon $createdAt The Timestamp the subaccount was created in Unbounce */
    private $createdAt;

    /** @var bool $isPagesLoaded Tells if the pages are already fully loaded */
    private $isPagesLoaded = false;

    /** @var Collection $pages All the pages from the SubAccount. */
    private $pages;

    /** @var string $state The State of the Subaccount. `unknown` (default), `active`, `inactive` */
    private $state;

    /**
     * SubAccount constructor.
     */
    public function __construct($id, $accountId)
    {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->state = 'unknown';
        $this->pages = new Collection();
    }

    public static function createFromApiData($apiData)
    {
        if (!isset($apiData->id) || empty($apiData->id)) {
            throw new \InvalidArgumentException("There must be an id given.");
        }

        return (new SubAccount($apiData->id, $apiData->account_id))->setName($apiData->name)
                                                                   ->setDomainsCount($apiData->domains_count)
                                                                   ->setCreatedAt(new Carbon($apiData->created_at));
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return SubAccount
     */
    public function setName(string $name): SubAccount
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getDomainsCount(): int
    {
        return $this->domainsCount;
    }

    /**
     * @param int $domainsCount
     *
     * @return SubAccount
     */
    public function setDomainsCount(int $domainsCount): SubAccount
    {
        $this->domainsCount = $domainsCount;

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
     * @return SubAccount
     */
    public function setCreatedAt(Carbon $createdAt): SubAccount
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @param bool $deepLoad
     *
     * @return Collection
     */
    public function getPages(bool $deepLoad = true): Collection
    {
        if ($this->isPagesLoaded) {
            return $this->pages;
        }

        $this->loadPages(false, $deepLoad);

        return $this->pages;
    }

    /**
     * @param bool $forceLoad should all pages be reloaded
     * @param bool $deepLoad  should all pages are loaded with deep-loading
     */
    private function loadPages(bool $forceLoad = false, bool $deepLoad = true): void
    {
        if ($this->isPagesLoaded && !$forceLoad) {
            return;
        }

        try {
            $this->pages = Unbounce::subaccountPages($this->id, $deepLoad);
            $this->state = Subaccount::stateActive;
        } catch (UnauthorizedApiException $e) {
            //    when we are unauthorized we assume, that this subaccount is inactive
            $this->pages = new Collection();
            $this->state = SubAccount::stateInactive;
        }
        $this->isPagesLoaded = true;
    }

    public function addPage(Page $page): SubAccount
    {
        $this->pages->push($page);
        $this->isPagesLoaded = true;

        return $this;
    }

    /**
     * @param bool $deepLoad load all pages with details
     */
    public function reloadPages(bool $deepLoad = true): void
    {
        $this->isPagesLoaded = false;
        $this->loadPages(true, $deepLoad);
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return SubAccount
     */
    public function setState(string $state): SubAccount
    {
        $this->state = $state;

        return $this;
    }

    public function getPublishedPageCount()
    {
        return $this->pages->filter(function (Page $page)
        {
            return $page->isPublished();
        })
                           ->count();
    }

    public function getUnpublishedPageCount()
    {
        return $this->pages->filter(function (Page $page)
        {
            return !$page->isPublished();
        })
                           ->count();
    }

    public function getPageCount()
    {
        return $this->pages->count();
    }

}