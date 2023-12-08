<?php 

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;


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
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->find($id);
        $seasons = $program->getSeasons();



        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id . ' found in program\'s table' 
            );
        }
        return $this->render('Program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }


    
    #[Route('/{programId}/season/{seasonId}', name: 'program_season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository)
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['id' => $seasonId, 'program' => $program]);
        
        return $this->render('seasons/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }
    #[Route('/{programId}/season/{seasonId}/episode/{episodeNumber}', name: 'program_season_episode_show')]
    public function showEpisode(int $programId, int $seasonId, int $episodeNumber, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository )
    {
        $program = $programRepository->findOneBy(['id' => $programId]);
        $season = $seasonRepository->findOneBy(['id' => $seasonId, 'program' => $program]);
        $episode = $episodeRepository->findOneBy(['id' => $episodeNumber, 'season' => $seasonId]);
        
        return $this->render('seasons/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}

