<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Normalizer;

use FileHandler\Bundle\FileHandlerBundle\FileRepositoryProvider;
use FileHandler\Bundle\FileHandlerBundle\Model\FileInterface;
use FileHandler\Bundle\FileHandlerBundle\Model\UploadedBase64File;
use FileHandler\Bundle\FileHandlerBundle\Service\Base64Uploader;
use FileHandler\Bundle\FileHandlerBundle\Service\FileFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class FileNormalizer implements ContextAwareDenormalizerInterface, CacheableSupportsMethodInterface, ContextAwareNormalizerInterface
{

    private ObjectNormalizer $normalizer;
    private Base64Uploader $uploader;

    private FileRepositoryProvider $repositoryProvider;
    private RouterInterface $router;
    private FileFactory $fileFactory;

    public function __construct(ObjectNormalizer $normalizer, Base64Uploader $uploader, FileRepositoryProvider $repositoryProvider, RouterInterface $router, FileFactory $fileFactory)
    {
        $this->normalizer = $normalizer;
        $this->uploader = $uploader;
        $this->repositoryProvider = $repositoryProvider;
        $this->router = $router;
        $this->fileFactory = $fileFactory;

    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }


    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {

        try{
            return in_array(FileInterface::class,class_implements($type));
        }catch(\Exception $exception){
            return false;
        }

        //return is_a($type, File::class, true);
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $context["groups"][]= "base64file:write";
        $repository = $this->repositoryProvider->get($type);
        if (isset($data["id"])) {
            $file  = $repository->find($data["id"]);
        } else {
            $base64file = $this->normalizer->denormalize($data, UploadedBase64File::class, $format, $context);
            $fileToUpload = $this->uploader->fromBase64File($base64file);
            $file = $this->fileFactory->create($fileToUpload, $repository, $base64file->getTitle(), $base64file->getDescription());
        }

        return $file;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof FileInterface;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $context["groups"][] = "file:read";
        return $this->normalizer->normalize($object,$format,$context);
        //$url = $this->router->generate('im_file', ['file' => (string)$object->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return array_merge($this->normalizer->normalize($object, $format, $context), ["thumbnailUrl" => $url]);
    }
}
