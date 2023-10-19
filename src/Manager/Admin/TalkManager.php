<?php

namespace App\Manager\Admin;

use App\DomainObject\TalkDomainObject;
use App\Entity\SpeakerTalk;
use App\Entity\Talk;
use App\Repository\SpeakerRepository;
use App\Repository\SpeakerTalkRepository;
use App\Repository\TalkRepository;
use App\Service\Search\SpeakerIndexer;
use App\Service\Search\TalkIndexer;
use App\Service\TalkSlugGenerator;

readonly class TalkManager
{
    public function __construct(
        private TalkRepository $talkRepository,
        private SpeakerRepository $speakerRepository,
        private SpeakerTalkRepository $speakerTalkRepository,
        private SpeakerIndexer $speakerIndexer,
        private TalkIndexer $talkIndexer,
        private TalkSlugGenerator $talkSlugGenerator
    ) {
    }

    public function createTalkFromDTO(TalkDomainObject $dto): Talk
    {
        $slug = $this->talkSlugGenerator->generateSlug($dto->name);

        $talk = (new Talk())
            ->setName($dto->name)(new Slugify())->slugify($dto->name)
            ->setSlug($slug)
            ->setDescription($dto->description)
            ->setDate($dto->date)
            ->setConferenceEdition($dto->conferenceEdition)
            ->setYoutubeId($dto->youtubeId);

        $this->talkRepository->save($talk);

        $this->talkIndexer->indexTalk($talk);

        return $talk;
    }

    public function updateTalkFromDTO(Talk $talk, TalkDomainObject $dto): Talk
    {
        $slug = $this->talkSlugGenerator->generateSlug($dto->name, $talk);

        $talk
            ->setName($dto->name)
            ->setSlug($slug)
            ->setDescription($dto->description)
            ->setConferenceEdition($dto->conferenceEdition);

        $speakers = [];
        if ($dto->speakerIds) {
            $speakers = $this->speakerRepository->findBy(['id' => explode(',', $dto->speakerIds)]);
        }

        foreach ($talk->getSpeakers() as $speakerTalk) {
            $this->speakerTalkRepository->remove($speakerTalk);
        }

        foreach ($speakers as $speaker) {
            $this->speakerTalkRepository->save((new SpeakerTalk())->setTalk($talk)->setSpeaker($speaker)->setMain(true));
        }

        $this->talkRepository->save($talk);

        $this->talkRepository->refresh($talk);
        $this->talkIndexer->indexTalk($talk);

        foreach ($speakers as $speaker) {
            $this->speakerIndexer->indexSpeaker($speaker);
        }

        return $talk;
    }

    public function removeTalk(Talk $talk): void
    {
        $id = $talk->getId();

        $this->talkRepository->remove($talk);

        $this->talkIndexer->removeTalkById($id);
    }
}
