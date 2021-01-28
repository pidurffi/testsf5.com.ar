<?php

namespace App\Entity;
use App\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\MarcadorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarcadorRepository::class)
 */
class Marcador
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Url
     * @AppAssert\UrlAccesible
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $categoria;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }
}
