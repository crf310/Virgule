<?php

namespace Virgule\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Virgule\Bundle\MainBundle\Entity\Salles
 *
 * @ORM\Table(name="salles")
 * @ORM\Entity
 */
class Salles
{
    /**
     * @var boolean $idSalle
     *
     * @ORM\Column(name="id_salle", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSalle;

    /**
     * @var string $nomSalle
     *
     * @ORM\Column(name="nom_salle", type="string", length=100, nullable=false)
     */
    private $nomSalle;

    /**
     * @var string $adresse
     *
     * @ORM\Column(name="adresse", type="string", length=250, nullable=true)
     */
    private $adresse;



    /**
     * Get idSalle
     *
     * @return boolean 
     */
    public function getIdSalle()
    {
        return $this->idSalle;
    }

    /**
     * Set nomSalle
     *
     * @param string $nomSalle
     */
    public function setNomSalle($nomSalle)
    {
        $this->nomSalle = $nomSalle;
    }

    /**
     * Get nomSalle
     *
     * @return string 
     */
    public function getNomSalle()
    {
        return $this->nomSalle;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}