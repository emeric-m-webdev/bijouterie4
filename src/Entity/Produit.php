<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{

    /*
        Entity = table (en bdd)
        Repository = requêtes (insert into / update / delete / select)

        Lorsqu'on créé une entity, est généré automatique son repository

        ORM : Object Relationnal Mapping
                ==> Relation entre la bdd et le projet (site)
        
        L'ORM de Symfony s'appelle Doctrine

        Pour que les propriétés soient enregistrées en bdd, il faut qu'elles aient l'annotation ORM



        les migrations :
        l'écoute entre la bdd et les entity
        toute différence engendrera un fichier "Version" dans le dossier migrations
        son contenu est du MySQL, qu'on enverra en bdd par le bias de Doctrine (ORM)

        Tout changement souhaité en bdd, s'effectue depuis les entity

        Les fichiers de migrations sont interprêtés qu'une SEULE FOIS
        une fois fait, ils sont listés dans la table doctrine_migration_version pour justement ne plus jamais être relus.

    */


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;




    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir un titre")
     * @Assert\Length(
     * min=5,
     * max=30,
     * minMessage="Veuillez saisir 5 caractères minimum",
     * maxMessage="Veuillez saisir 30 caractères maximum"
     * )
     */
    private $titre;




    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veuillez saisir un prix")
     * @Assert\Positive(message="Veuillez saisir un prix supérieur à zéro")
     */
    private $prix;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $dateAt;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @Assert\NotBlank(message="Veuillez saisir une catégorie")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=Marque::class, inversedBy="produits")
     * @Assert\NotBlank(message="Veuillez saisir une marque")
     */
    private $marque;

    /**
     * @ORM\ManyToMany(targetEntity=Matiere::class, inversedBy="produits")
     * @Assert\Count(
     *   min = 1,
     *   minMessage = "Sélectionner une matière au minimum"
     * )
     */
    private $matiere;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="produit")
     */
    private $commentaires;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez saisir un stock")
     * @Assert\PositiveOrZero(message="Veuillez saisir un stock supérieur ou égal à zéro")
     */
    private $stock;

    public function __construct()
    {
        $this->matiere = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateAt(): ?\DateTimeImmutable
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeImmutable $dateAt): self
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatiere(): Collection
    {
        return $this->matiere;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matiere->contains($matiere)) {
            $this->matiere[] = $matiere;
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        $this->matiere->removeElement($matiere);

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setProduit($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getProduit() === $this) {
                $commentaire->setProduit(null);
            }
        }

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
