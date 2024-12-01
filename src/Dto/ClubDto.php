<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
class ClubDto
{
    #[Vich\UploadableField(mapping: 'logo', fileNameProperty: 'logoName', size: 'logoSize')]
    public ?File $logoFile = null;

    public function __construct(
        public ?int                $id = null,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public ?string             $name = null,

        #[Assert\Length(max: 20)]
        public ?string             $federationNumber = null,

        #[Assert\NotBlank(message: 'Le numéro de téléphone ne peut pas être vide')]
        #[Assert\Regex(
            pattern: '/^(\+32|0)(?:2|3|4|9)\d{8}$/',
            message: 'Le numéro de téléphone belge est invalide'
        )]
        public ?string             $phoneNumber = null,

        public ?string             $description = null,

// Logo details
        public ?string             $logoName = null,
        public ?int                $logoSize = null,
//        #[Vich\UploadableField(mapping: 'logo', fileNameProperty: 'logoName', size: 'logoSize')]
//        public ?File               $logoFile = null,

// Image details
        public ?string             $imageName = null,
        public ?int                $imageSize = null,

        #[Assert\NotNull]
        public ?\DateTimeImmutable $createdAt = null,

        public ?\DateTimeImmutable $updatedAt = null,

// Address details
        public ?string             $street = null,
        public ?string             $streetNumber = null,

        #[Assert\Regex(pattern: '/^\d{4,5}$/', message: 'Invalid postal code.')]
        public ?string             $postCode = null,

        public ?string             $city = null,

        #[Assert\Email]
        public ?string             $email = null,

        #[Assert\Regex(pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Invalid slug format.')]
        public ?string             $slug = null,
    )
    {
    }

    public function setLogoFile(?File $logoFile = null): void
    {
        $this->logoFile = $logoFile;

        if (null !== $logoFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

}
