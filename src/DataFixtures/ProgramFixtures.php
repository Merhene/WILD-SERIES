<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface

{
    public const PROGRAMS = [
        ['title' => 'Breaking Bad', 'synopsis' => 'Pour subvenir aux besoins de Skyler, sa femme enceinte, et de Walt Junior, son fils handicapé, il est obligé de travailler doublement. Son quotidien déjà morose devient carrément noir lorsqu\'il apprend qu\'il est atteint d\'un incurable cancer des poumons.', 'category' => 'category_Drame'],
        ['title' => 'Dexter', 'synopsis' => 'Brillant expert scientifique du service médico-légal de la police de Miami, Dexter Morgan est spécialisé dans l\'analyse de prélèvements sanguins. Mais voilà, Dexter cache un terrible secret : il est également tueur en série. Un serial killer pas comme les autres, avec sa propre vision de la justice.', 'category' => 'category_Policier'],
        ['title' => 'Band of Brothers', 'synopsis' => 'Vivez la Seconde Guerre Mondiale aux côtés de la Easy Company, un groupe de soldats américains. Suivez-les en tant que groupe, ou individuellement, depuis leur formation en 1942, jusqu\'à la libération de l\'Allemagne Nazie en 1945, en passant par leur parachutage en Normandie le 6 juin 1944.', 'category' => 'category_Drame'],
        ['title' => 'Snow Fall', 'synopsis' => 'En 1983, le trafic de cocaïne règne en maître dans la Cité des anges et distille ses ravages dans toutes les couches de la société. Pauvreté, violence, drogue et prostitution constituent l\'ADN de la ville, tandis que la ségrégation raciale bat toujours son plein.', 'category' => 'category_Drame'],
        ['title' => 'Yellowstone', 'synopsis' => 'John Dutton, possède le plus grand Ranch du Montana non loin du Yellowstone. Aidé par ses enfants, il se bat pour conserver son bien menacé par l\'appétit féroce des promoteurs de tous poils sans oublier les Indiens qui revendiquent un droit', 'category' => 'category_Aventure'],
        ['title' => 'Terminal List', 'synopsis' => 'Au cours d\'une mission secrète, l\'escouade de Navy SEAL dont faisait parti Reece est décimée. Unique survivant, il rentre chez lui avec des symptômes de stress post-traumatique. Ses souvenirs sont contradictoires et flous sur l\'événement et il se sent terriblement coupable.', 'category' => 'category_Drame'],
    ];

    public function load(ObjectManager $manager)
    {

        foreach (self::PROGRAMS as $key => $programName) {
            $program = new Program();
            $program->setTitle($programName['title']);
            $program->setSynopsis($programName['synopsis']);
            $program->setCategory($this->getReference($programName['category']));
            $manager->persist($program);
            // $this->addReference('category_' . $programName, $program);


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


