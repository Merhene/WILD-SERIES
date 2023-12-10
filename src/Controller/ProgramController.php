<?php 

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;


class ProgramController extends AbstractController

{
    #[Route('/programs', name: 'program_index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('Program/index.html.twig', [
            'programs' => $programs]
        );
    }


    #[Route('/program/{id}', requirements: ['id'=>'\d+'], methods: ['GET'], name: 'program_show')]
    public function show(Program $program): Response
    {
        // $seasons = $program->getSeasons();

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program . ' found in program\'s table' 
            );
        }
        return $this->render('Program/show.html.twig', ['program' => $program]);
    }

    #[Route('/{program}/season/{season}', name: 'program_season_show')]
    public function showSeason(Program $program, Season $season): Response
    {
        if (!$program) {
            throw $this->createNotFoundException('Program not found');
        }

        if (!$season) {
            throw $this->createNotFoundException('Season not found for this program');
        }
        
        return $this->render('seasons/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }
    #[Route('/{program_id}/season/{season_id}/episode/{episode_id}', name: 'episode_show')]
    public function showEpisode(
        #[MapEntity(mapping: ['program_id' => 'id'])] Program $program, 
        #[MapEntity(mapping: ['season_id' => 'id'])] Season $season,
        #[MapEntity(mapping: ['episode_id' => 'id'])] Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}

