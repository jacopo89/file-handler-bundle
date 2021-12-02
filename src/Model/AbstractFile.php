<?php


namespace FileHandler\Bundle\FileHandlerBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @MappedSuperclass
 */
abstract class AbstractFile implements FileInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("file:read")
     *
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("file:read")
     *
     */
    protected string $filename;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    protected string $ext;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("file:read")
     *
     */
    protected string $subDir;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("file:read")
     *
     */
    protected string $path;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("file:read")
     */
    protected string $url;

    /**
     * @ORM\Column(type="integer")
     *
     */
    protected ?int $weight = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     */
    protected ?int $width = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     */
    protected ?int $height = null;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    protected string $md5;

    /**
     * @var string|null
     * @ORM\Column (type="string", length=255, nullable=true)
     * @Groups ("file:read")
     */
    protected ?string $title = null;

    /**
     * @var string|null
     * @ORM\Column (type="string", length=255, nullable=true)
     * @Groups ("file:read")
     */
    protected ?string $description = null;

    /**
     * @var string|null
     * @ORM\Column (type="string", length=255, nullable=true)
     *
     */
    protected ?string $mimeType = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getExt(): ?string
    {
        return $this->ext;
    }

    public function setExt(string $ext): self
    {
        $this->ext = $ext;

        return $this;
    }

    public function getSubDir(): ?string
    {
        return $this->subDir;
    }

    public function setSubDir(string $subDir): self
    {
        $this->subDir = $subDir;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getMd5(): ?string
    {
        return $this->md5;
    }

    public function setMd5(string $md5): self
    {
        $this->md5 = $md5;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    final public static function createFromFileModel(FileToUpload $fileModel, string $path, string $url, string $title = null, string $description = null)
    {
        $self = new static();
        $self->filename = $fileModel->getFilename();
        $self->ext = $fileModel->getFileInfo()->getExt();
        $self->subDir = $fileModel->getSubDir();
        $self->path = $path;
        $self->url = $url;
        $self->weight = $fileModel->getFileInfo()->getWeight();
        $self->width = $fileModel->getFileInfo()->getWidth();
        $self->height = $fileModel->getFileInfo()->getHeight();
        $self->md5 = $fileModel->getMd5();
        $self->mimeType = $fileModel->getFileInfo()->getMimeType();
        $self->title = $title;
        $self->description = $description;
        return $self;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function isImage(): bool
    {
        return strpos($this->mimeType, "image/") === 0;
    }

    public function isPDF(): bool
    {
        return strpos($this->mimeType, "application/pdf") === 0;
    }
}
