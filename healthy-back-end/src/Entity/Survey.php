<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 */
class Survey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read_survey","readAnswer"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read_survey","readAnswer"})
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="survey",cascade={"persist","remove"},orphanRemoval=true)
     * @Groups({"read_survey"})
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="surveys")
     * @Groups({"read_survey"})
     */
    private $createdBy;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read_survey"})
     */
    private $feedback;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Answer", mappedBy="survey", cascade={"persist", "remove"})
     */
    private $answer;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    /**
     * @param $questions
     */
    public function setQuestions($questions)
    {
        foreach ($questions as $question) {
            $q = new Question();
            $q->setTitle($question['title']);
            $q->setResponse($question['title']);
            $q->setSurvey($this);
            $this->questions[] = $q;
        }
    }
    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $question->setSurvey($this);
            $this->questions[] = $question;
        }
        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getSurvey() === $this) {
                $question->setSurvey(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
    public function getFeedback(): ?string
    {
        return $this->feedback;
    }
    public function setFeedback(?string $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }
    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }
    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;
        // set (or unset) the owning side of the relation if necessary
        return $this;
    }
}
