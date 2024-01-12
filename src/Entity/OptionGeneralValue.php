<?php

namespace App\Entity;

use App\Repository\OptionGeneralValuesRepository;
use App\Traits\Entity\UuidTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OptionGeneralValuesRepository::class)]
#[ORM\Table(name: '`option_general_value`')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ORM\HasLifecycleCallbacks()]
class OptionGeneralValue
{
    use UuidTrait;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['option_value_basic'])]
    private string $text;

    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    #[Groups(['option_value_basic'])]
    private string $tag;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['option_value_basic'])]
    private ?string $description;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['option_value_basic'])]
    private array $parameters;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    #[Groups(['option_value_basic'])]
    private int $position;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    #[Groups(['option_value_basic'])]
    private bool $enabled;

    #[ORM\ManyToOne(targetEntity: OptionGeneral::class, inversedBy: 'values')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private OptionGeneral $group;

    public function toArray(bool $withGroup = true): array
    {
        $array = [
            'id' => $this->getUuid(),
            'text' => $this->getText(),
            'tag' => $this->getTag(),
            'description' => $this->getDescription(),
            'parameters' => $this->getParameters(),
            'enabled' => $this->isEnabled(),
            'position' => $this->getPosition(),
        ];

        if ($withGroup) {
            return array_merge($array, ['group' => $this->getGroup()->toArray(false)]);
        }

        return $array;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTag(): string
    {
        return $this->getParameter('tag', $this->tag);
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

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

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter($key, $default = null)
    {
        $parameters = $this->getParameters();

        return $parameters[$key] ?? $default;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = array_replace_recursive($this->parameters, $parameters);

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getGroup(): OptionGeneral
    {
        return $this->group;
    }

    public function setGroup(OptionGeneral $group): self
    {
        $this->group = $group;

        return $this;
    }
}
