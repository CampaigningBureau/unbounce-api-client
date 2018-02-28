<?php

namespace CampaigningBureau\UnbounceApiClient;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Unbounce;

class SubAccount
{

    const stateUnknown = 'unknown', stateActive = 'active', stateInactive = 'inactive';

    /**
     * @var string $id Id of the Subaccount in Unbounce
     */
    private $id;

    /**
     * @var string $name The name of the subaccount
     */
    private $name;

    /**
     * @var string $accountId The id of the account the subaccount belongs to.
     */
    private $accountId;

    /**
     * @var int $domainsCount The count of domains this subaccount uses
     */
    private $domainsCount;

    /**
     * @var Carbon $createdAt The Timestamp the subaccount was created in Unbounce
     */
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
    }

    public static function createFromApiData($apiData)
    {
        if (!isset($apiData->id) || empty($apiData->id))
        {
            throw new \InvalidArgumentException("There must be an id given.");
        }

        return (new SubAccount($apiData->id, $apiData->accountId))->setName($apiData->name)
                                                                  ->setDomainsCount($apiData->domainsCount)
                                                                  ->setCreatedAt(new Carbon($apiData->createdAt));
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
     * @param int $domainCount
     *
     * @return SubAccount
     */
    public function setDomainsCount(int $domainsCount): SubAccount
    {
        $this->domainsCount = $domainsCount;

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
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getPages(): Collection
    {
        if ($this->isPagesLoaded)
        {
            return $this->pages;
        }

        $this->loadPages();

        return $this->pages;
    }

    private function loadPages(bool $forceLoad = false): void
    {
        if ($this->isPagesLoaded && !$forceLoad)
        {
            return;
        }


        try
        {
            $this->pages = Unbounce::subaccountPages($this->id);
            $this->state = Subaccount::stateActive;
        } catch (UnauthorizedApiException $e)
        {
            //    when we are unauthorized we assume, that this subaccount is inactive
            $this->pages = new Collection();
            $this->state = SubAccount::stateInactive;
        }
        $this->isPagesLoaded = true;
    }

    public function reloadPages(): void
    {
        $this->isPagesLoaded = false;
        $this->loadPages(true);
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    public function getActivePageCount()
    {
        return $this->pages->filter(
            function (Page $page)
            {
                return $page->isPublished();
            }
        )
                           ->count();
    }

    public function getInactivePageCount()
    {
        return $this->pages->filter(
            function (Page $page)
            {
                return !$page->isPublished();
            }
        )
                           ->count();
    }

}