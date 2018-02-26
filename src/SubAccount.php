<?php

namespace CampaigningBureau\UnbounceApiClient;


use CampaigningBureau\UnbounceApiClient\Test\Responses\SubaccountIndexStandardResponse;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Unbounce;

class SubAccount
{

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

    /**
     * SubAccount constructor.
     */
    public function __construct($id, $accountId)
    {
        $this->id = $id;
        $this->accountId = $accountId;
    }

    public static function createFromApiData($apiData)
    {
        if (!isset($apiData->id) || empty($apiData->id))
        {
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

        $this->pages = Unbounce::subaccountPages($this->id);
        $this->isPagesLoaded = true;
    }

    public function reloadPages(): void
    {
        $this->isPagesLoaded = false;
        $this->loadPages(true);
    }
}