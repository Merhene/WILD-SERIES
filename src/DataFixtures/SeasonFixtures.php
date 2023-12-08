<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface

{
    // public const SEASONS = [
    //     ['number' => '1', 'year' => '2009', 'description' => 'série avocat', 'program' => 'program_Dexter'],
    //     ['number' => '2', 'year' => '2010', 'description' => 'série avocat', 'program' => 'program_Dexter'],
    //     ['number' => '3', 'year' => '2011', 'description' => 'série avocat', 'program' => 'program_Dexter'],
    //     ['number' => '4', 'year' => '2012', 'description' => 'série avocat', 'program' => 'program_Dexter'],
    // ];

    public function load(ObjectManager $manager)
    {
        // foreach(self::SEASONS as $seasonList) {
        $faker = Factory::Create();
        foreach (ProgramFixtures::PROGRAMS as $programList) {
            for($i = 1; $i <=5; $i++) { 
            $season = new Season();
            $season->setNumber($i);
            $season->setYear($faker->year());
            $season->setDescription($faker->paragraphs(3, true));
            $season->setProgram($this->getReference('program_' . $programList['title']));
            $this->addReference('program_' . $programList['title'] . 'season_' . $i, $season);
            $manager->persist($season);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
