<?php

namespace France\TvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="France\TvBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Hateoas\Relation(
 *      "_self",
 *      href = @Hateoas\Route(
 *          "get_article",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 *  @Hateoas\Relation(
 *      "_remove",
 *      href = @Hateoas\Route(
 *          "remove_articles",
 *          parameters = {"id" = "expr(object.getId())" }
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "get_articles",
 *          parameters = {}
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "post_articles",
 *      href = @Hateoas\Route(
 *          "post_articles",
 *          parameters = {}
 *      )
 * )
 *
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="Title", type="string", length=255, nullable=false)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="leadingTest", type="text", nullable=true)
     */
    private $leading;
    
    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * @Gedmo\Slug(fields={"title","id"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="createdBy", type="text", nullable=true)
     */
    private $createdBy;
    
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * Get leading
     *
     * @return string
     */
    public function getLeading()
    {
        return $this->leading;
    }
    
    /**
     * Set leading
     *
     * @param string $leading
     *
     * @return Article
     */
    public function setLeading($leading)
    {
        $this->leading = $leading;
        
        return $this;
    }
    
    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * Set body
     *
     * @param string $body
     *
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;
        
        return $this;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function updateDate()
    {
        $this->setCreatedAt(new \Datetime());
    }
    
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Article
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    
    /**
     * Set createdBy
     *
     * @param string $createdBy
     *
     * @return Article
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->title;
    }

    public function getDynamicHref() {
        return "dynamic/Href/here";
    }
    
}

