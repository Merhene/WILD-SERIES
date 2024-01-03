<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProgramFixtures extends Fixture implements DependentFixtureInterface

{

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger=$slugger;
    }
    
    public const PROGRAMS = [
        ['title' => 'Breaking Bad', 'synopsis' => 'Pour subvenir aux besoins de Skyler, sa femme enceinte, et de Walt Junior, son fils handicapé, il est obligé de travailler doublement. Son quotidien déjà morose devient carrément noir lorsqu\'il apprend qu\'il est atteint d\'un incurable cancer des poumons.', 'category' => 'category_Drame'],
        ['title' => 'Dexter', 'synopsis' => 'Brillant expert scientifique du service médico-légal de la police de Miami, Dexter Morgan est spécialisé dans l\'analyse de prélèvements sanguins. Mais voilà, Dexter cache un terrible secret : il est également tueur en série. Un serial killer pas comme les autres, avec sa propre vision de la justice.', 'category' => 'category_Policier'],
        ['title' => 'Band of Brothers', 'synopsis' => 'Vivez la Seconde Guerre Mondiale aux côtés de la Easy Company, un groupe de soldats américains. Suivez-les en tant que groupe, ou individuellement, depuis leur formation en 1942, jusqu\'à la libération de l\'Allemagne Nazie en 1945, en passant par leur parachutage en Normandie le 6 juin 1944.', 'category' => 'category_Drame'],
        ['title' => 'Snow Fall', 'synopsis' => 'En 1983, le trafic de cocaïne règne en maître dans la Cité des anges et distille ses ravages dans toutes les couches de la société. Pauvreté, violence, drogue et prostitution constituent l\'ADN de la ville, tandis que la ségrégation raciale bat toujours son plein.', 'category' => 'category_Drame'],
        ['title' => 'Yellowstone', 'synopsis' => 'John Dutton, possède le plus grand Ranch du Montana non loin du Yellowstone. Aidé par ses enfants, il se bat pour conserver son bien menacé par l\'appétit féroce des promoteurs de tous poils sans oublier les Indiens qui revendiquent un droit', 'category' => 'category_Aventure'],
        ['title' => 'Terminal List', 'synopsis' => 'Au cours d\'une mission secrète, l\'escouade de Navy SEAL dont faisait parti Reece est décimée. Unique survivant, il rentre chez lui avec des symptômes de stress post-traumatique. Ses souvenirs sont contradictoires et flous sur l\'événement et il se sent terriblement coupable.', 'category' => 'category_Drame'],
        ['title' => 'Malcolm', 'synopsis' => 'Petit génie malgré lui, Malcolm vit dans une famille hors du commun. Le jeune surdoué n\'hésite pas à se servir de son intelligence pour faire les 400 coups avec ses frères. Et les parents tentent tant bien que mal de canaliser l\'énergie de ces petits démons.', 'category' => 'category_Comédie'],
        ['title' => 'American Dad', 'synopsis' => 'Agent de la CIA, Stan Smith est aussi un époux comblé et un fier papa. Le problème c\'est que son fils a une légère tendance à verser dans la paranoïa. Prêt à tout pour défendre sa patrie, il n\'hésiterait pas à tirer sur son grille-pain si celui-ci était suspecté de trahison.', 'category' => 'category_Comédie'],
    ];

    public function load(ObjectManager $manager)
    {

        foreach (self::PROGRAMS as $key => $programName) {
            $program = new Program();
            $program->setTitle($programName['title']);
            $slug = $this->slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setSynopsis($programName['synopsis']);
            $program->setCategory($this->getReference($programName['category']));
            $manager->persist($program);
            $this->addReference('program_' . $programName['title'], $program);

        }

                $manager->flush();

    }

        public function getDependencies()
    {
        return [
          CategoryFixtures::class,
        ];
    }

    }


