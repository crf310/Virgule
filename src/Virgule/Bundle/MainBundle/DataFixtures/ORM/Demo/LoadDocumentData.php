<?php
namespace Virgule\Bundle\MainBundle\DataFixtures\ORM\Demo;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Virgule\Bundle\MainBundle\Entity\Document;

/**
 * Description of LoadDocumentData
 *
 * @author Guillaume Lucazeau
 */

class LoadDocumentData extends AbstractFixture implements OrderedFixtureInterface {
    public function load(ObjectManager $manager) {  
        
        $classLevels = Array(
            $this->getReference('A1'),
            $this->getReference('A2'),
            $this->getReference('Alpha'),
            $this->getReference('B1/1'),
            $this->getReference('B1/2'),
            $this->getReference('B2'),
            $this->getReference('B3'),
        );
        
        for ($i = 1; $i <= 50; $i++) {
            $document = new Document();
            $document->setFileName('Document ' . $i);
            $document->setDescription('Description du document ' . $i);
            $document->setUploader($this->getReference('prof' . rand(1,4)));
            $document->setUploadDate(new \Datetime('now'));
            $document->setPath('document_' . $i . '.pdf');
            $document->addClassLevel($classLevels[rand(0, count($classLevels)-1)]);
            
            $manager->persist($document);
        }
        
        $manager->flush();
    }
    
    public function getOrder() {
        return 14;
    }
}

?>
