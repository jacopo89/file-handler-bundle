<?php


namespace FileHandler\Bundle\FileHandlerBundle\Controller;


use FileHandler\Bundle\FileHandlerBundle\FileRepositoryProvider;
use FileHandler\Bundle\FileHandlerBundle\Model\AbstractFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/im")
 */
class ImageController extends AbstractController
{
    private string $publicDir;
    private FileRepositoryProvider $fileRepositoryProvider;

    public function __construct(string $projectDir,FileRepositoryProvider $fileRepositoryProvider)
    {
        $this->publicDir = sprintf("%s/%s",$projectDir, "public");
        $this->fileRepositoryProvider = $fileRepositoryProvider;
    }

    /**
     * @Route ("/{resourceName}/{id}/{slug?}", name="im_file", methods={"GET"})
     */
    public function getImage(Request $request,$resourceName, $id, string $slug = null): Response
    {
        $fileRepository = $this->fileRepositoryProvider->get($resourceName);
        $file = $fileRepository->find($id);
        $path = sprintf("%s/%s/%s", $this->publicDir, $fileRepository->getSubDir(), $file->getPath());
        if (! $file->isImage()) return BinaryFileResponse::create($path);
        $w = $request->query->get('w', null);
        $supportWebp = $this->supportWebp($request);

        $md5Name = $supportWebp ? $file->getFilename() . "_w" . $w . ".webp" : $file->getFilename() . "_w" . $w;
        $md5FileName = sprintf("%s.%s", md5($md5Name), $supportWebp ? "webp" : $file->getExt());

        $folderRelativePath = sprintf("files/thumbnails/%s", $fileRepository->getSubDir());

        $newFilePath = sprintf("%s/%s",$folderRelativePath, $md5FileName);
        if (file_exists($newFilePath)) return BinaryFileResponse::create($newFilePath);

        copy(sprintf("%s/%s", $this->publicDir, $file->getPath()), $newFilePath);
        $image = Image::load($newFilePath);
        if ($w) {
            $image->width((int)$w);
        }
        if ($supportWebp) {
            $image->format(Manipulations::FORMAT_WEBP);
        }
        $image->quality(75)->optimize()->save();
        return BinaryFileResponse::create($newFilePath);
    }

    private function supportWebp(Request $request): bool
    {
        $acceptableContent = $request->getAcceptableContentTypes();
        foreach ($acceptableContent as $format) {
            if ('image/webp' == $format) {
                return true;
            }
        }

        if ($request->query->get('format', null) == "webp") return true;

        return false;
    }
}
