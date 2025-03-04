<?php

namespace App\Factory;

use App\Enum\SearchClientEnum;
use App\Service\Search\Client\SearchClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class SearchClientFactory
{
    public function __construct(
        #[AutowireIterator('search.client')]
        /** @var iterable<SearchClientInterface> */
        private iterable $searchClients,
        #[Autowire(env: 'SEARCH_CLIENT')]
        private string $searchClientName,
    ) {
    }

    public function create(): SearchClientInterface
    {
        $searchClientEnum = SearchClientEnum::from($this->searchClientName);

        foreach ($this->searchClients as $searchClient) {
            if ($searchClient->supports($searchClientEnum)) {
                return $searchClient;
            }
        }

        throw new \RuntimeException('No search client found');
    }
}
