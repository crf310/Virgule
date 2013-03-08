<?php

namespace Virgule\Bundle\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuBuilder extends ContainerAware {

    public function mainMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        $menu->addChild('Accueil', array('route' => 'welcome'));
        $menu['Accueil']->setLinkAttribute('class', 'welcome');
        
        $menu->addChild('Compte-rendus', array('route' => 'classsession_index'));
        $menu['Compte-rendus']->setLinkAttribute('class', 'minutes');
        
        $menu->addChild('Planning des cours', array('route' => 'course_index'));
        $menu['Planning des cours']->setLinkAttribute('class', 'schedule');
        
        $menu->addChild('Apprenants', array('route' => 'student_index'));
        $menu['Apprenants']->setLinkAttribute('class', 'students');
        
        $menu->addChild('Formateurs', array('route' => 'teacher_index'));
        $menu['Formateurs']->setLinkAttribute('class', 'teachers');
        
        $menu->addChild('Statistiques', array('route' => 'stats_index'));
        $menu['Statistiques']->setLinkAttribute('class', 'statistics');
        
        $menu->addChild('Administration', array('route' => 'admin_show_logs'));
        $menu['Administration']->setLinkAttribute('class', 'administration');
        
        return $menu;
    }
}