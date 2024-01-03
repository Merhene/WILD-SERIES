<?php

namespace App\Service;

use App\Entity\Program;
use App\Entity\Season;

class ProgramDuration
{
    public function calculate(Program $program): int
    {
        $programDuration = 0;

        $seasons = $program->getSeasons();

        foreach ($seasons as $season) {
            $programDuration = $this->calculateSeason($season);
        }
        return $programDuration;
    }

    public function calculateSeason(Season $season): int 
    {
        $programDuration2 = 0;

        $episodes = $season->getEpisodes();

        foreach ($episodes as $episode) {
            $programDuration2 += $episode->getDuration();
        }
        return $programDuration2;
    }
}