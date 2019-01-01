<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\auteur;
use AppBundle\Entity\livre;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class LoadFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

         // create 20 products! Bam!
         for ($i = 0; $i < 20; $i++) {
            $auteur = new auteur();
            $auteur->setNom('Heni '.$i);
            $auteur->setPrenom(' Ben Lazreg');
            $auteur->setEmail('heni'.$i.'benlazreg@heni.com');
            $manager->persist($auteur);

            $livre = new livre();
            $livre->setTitre('livre '.$i);
            $livre->setDescriptif('Le livre '.$i.'est un des plus beau livres.');
            $livre->setISBN('ISBN '.$i);
            $livre->setDateEdition(date_create(date("Y-m-d")));
            $livre->setAuteur($auteur);
            $manager->persist($livre);

            $livre2 = new livre();
            $livre2->setTitre('livre '.$i.'.2');
            $livre2->setDescriptif('Le livre '.$i.'.2 est un des plus beau livres.');
            $livre2->setISBN('ISBN '.$i.'.2');
            $livre2->setDateEdition(date_create(date("Y-m-d")));
            $livre2->setAuteur($auteur);
            $manager->persist($livre2);
        }

        $manager->flush();
    }
}
?>