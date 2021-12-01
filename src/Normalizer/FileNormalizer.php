<?php
declare(strict_types=1);

namespace FileHandler\Bundle\FileHandlerBundle\Normalizer;

use FileHandler\Bundle\FileHandlerBundle\FileRepositoryProvider;
use FileHandler\Bundle\FileHandlerBundle\Model\FileInterface;
use FileHandler\Bundle\FileHandlerBundle\Model\UploadedBase64File;
use FileHandler\Bundle\FileHandlerBundle\Service\Base64Uploader;
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

    public function __construct(ObjectNormalizer $normalizer, Base64Uploader $uploader, FileRepositoryProvider $repositoryProvider, RouterInterface $router)
    {
        $this->normalizer = $normalizer;
        $this->uploader = $uploader;
        $this->repositoryProvider = $repositoryProvider;
        $this->router = $router;

    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }


    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return class_implements($type, FileInterface::class);
        //return is_a($type, File::class, true);
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $repository = $this->repositoryProvider->get($type);
        if (isset($data["id"])) {
            $file  = $repository->find($data["id"]);
        } else {
            $base64file = $this->normalizer->denormalize($data, UploadedBase64File::class, $format, $context);
            $file = $this->uploader->fromBase64File($base64file);
        }

        return $file;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof FileInterface;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $url = $this->router->generate('im_file', ['file' => (string)$object->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return array_merge($this->normalizer->normalize($object, $format, $context), ["thumbnailUrl" => $url]);
    }
}
