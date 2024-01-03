<?php 

namespace App\Controller;

use App\Form\ProgramType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Service\ProgramDuration;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/program')]
class ProgramController extends AbstractController

{
    #[Route('/programs', name: 'program_index')]
    public function index(ProgramRepository $programRepository, RequestStack $requestStack) : Response
    {
        $session = $requestStack->getSession();
        if (!$session->has('total')) {
            $session->set('total', 0); 
        }
    
        $total = $session->get('total'); 
        $programs = $programRepository->findAll();

        return $this->render('Program/index.html.twig', [
            'programs' => $programs]
        );
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $program = new Program();

        // Create the form, linked with $category
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        // Was the form submitted ?
            if ($form->isSubmitted() && $form->isValid()) {
                $slug = $slugger->slug($program->getTitle());
                $program->setSlug($slug);
                $entityManager->persist($program);

                $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée!')
                ->html($this->renderView('Program/newProgramEmail.html.twig',
                ['program' => $program]));

                $mailer->send($email);

                $entityManager->flush();
                $this->addFlash('success', 'The new program has been created');
                return $this->redirectToRoute('program_index');
            }

        return $this->render('program/new.html.twig', [
            'form' => $form,
            'program' => $program,
        ]);
    }
    

    #[Route('/{slug}', methods: ['GET'], name: 'program_show')]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        // $seasons = $program->getSeasons();

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program . ' found in program\'s table' 
            );
        }
        return $this->render('Program/show.html.twig', [
            'program' => $program,
            'slug' => $program->getSlug(),
            'programDuration' => $programDuration->calculate($program)]);
    }

    #[Route('/{slug}/season/{season}', name: 'program_season_show')]
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
    #[Route('/{slug}/season/{season_id}/episode/{episode_id}', name: 'episode_show')]
    public function showEpisode(
        #[MapEntity(mapping: ['slug' => 'slug'])] Program $program, 
        #[MapEntity(mapping: ['season_id' => 'id'])] Season $season,
        #[MapEntity(mapping: ['episode_id' => 'id'])] Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The new program has been updated');

            return $this->redirectToRoute('program_index', [],
            Response::HTTP_SEE_OTHER);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

        #[Route('/{id}', name: 'delete', methods: ['POST'])]
        public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
        {
            if ($this->isCsrfTokenValid('delete' .$program->getId(), $request->request->get('_token'))) {
                $entityManager->remove($program);
                $entityManager->flush();
                $this->addFlash('danger', 'Deleted !');
            }
            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

}

