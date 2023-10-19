<?php

namespace App\Manager\Admin;

use App\DomainObject\ConferenceEditionDomainObject;
use App\DomainObject\ConferenceEditionNotificationDomainObject;
use App\Entity\ConferenceEdition;
use App\Entity\ConferenceEditionNotification;
use App\Message\ImportYoutubePlaylistMessage;
use App\Repository\ConferenceEditionNotificationRepository;
use App\Repository\ConferenceEditionRepository;
use App\Repository\YoutubePlaylistImportRepository;
use App\Service\ConferenceEditionSlugGenerator;
use App\Service\Search\ConferenceEditionIndexer;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ConferenceEditionManager
{
    public function __construct(
        private ConferenceEditionRepository $conferenceEditionRepository,
        private ConferenceEditionNotificationRepository $conferenceEditionNotificationRepository,
        private YoutubePlaylistImportRepository $youtubePlaylistImportRepository,
        private MessageBusInterface $bus,
        private ConferenceEditionIndexer $conferenceEditionIndexer,
        private ConferenceEditionSlugGenerator $conferenceEditionSlugGenerator
    ) {
    }

    public function createConferenceEditionFromDTO(ConferenceEditionDomainObject $dto): ConferenceEdition
    {
        $slug = $this->conferenceEditionSlugGenerator->generateSlug($dto->name);
        $conferenceEdition = (new ConferenceEdition())
            ->setName($dto->name)
            ->setSlug($slug)
            ->setDescription($dto->description)
            ->setStartDate($dto->startDate)
            ->setEndDate($dto->endDate)
            ->setConference($dto->conference);

        $this->conferenceEditionRepository->save($conferenceEdition);

        $this->indexConferenceEdition($conferenceEdition);

        return $conferenceEdition;
    }

    public function updateConferenceEditionFromDTO(ConferenceEdition $conferenceEdition, ConferenceEditionDomainObject $dto): ConferenceEdition
    {
        $slug = $this->conferenceEditionSlugGenerator->generateSlug($dto->name, $conferenceEdition);
        $conferenceEdition
            ->setName($dto->name)
            ->setSlug($slug)
            ->setDescription($dto->description)
            ->setStartDate($dto->startDate)
            ->setEndDate($dto->endDate);

        $this->conferenceEditionRepository->save($conferenceEdition);

        $this->indexConferenceEdition($conferenceEdition);

        return $conferenceEdition;
    }

    private function indexConferenceEdition(ConferenceEdition $edition): void
    {
        $this->conferenceEditionIndexer->indexConferenceEdition($edition);
    }

    public function createConferenceEditionNotificationFromDTO(ConferenceEditionNotificationDomainObject $dto): ConferenceEditionNotification
    {
        $conferenceEditionNotification = $this
            ->conferenceEditionNotificationRepository
            ->findOneBy([
                'conferenceEdition' => $dto->conferenceEdition->getId(),
                'email' => $dto->email
            ]);

        if (null !== $conferenceEditionNotification) {
            return $conferenceEditionNotification;
        }

        $conferenceEditionNotification = (new ConferenceEditionNotification())
            ->setEmail($dto->email)
            ->setConferenceEdition($dto->conferenceEdition);

        $this->conferenceEditionNotificationRepository->save($conferenceEditionNotification);

        return $conferenceEditionNotification;
    }

    public function refreshTalks(ConferenceEdition $conferenceEdition): void
    {
        $playlistImports = $this->youtubePlaylistImportRepository->findBy(['conferenceEdition' => $conferenceEdition]);

        foreach ($playlistImports as $playlistImport) {
            $this->bus->dispatch(new ImportYoutubePlaylistMessage($playlistImport->getId()));
        }
    }
}
