<?php

namespace Nfq\Fairytale\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator;
use Nfq\Fairytale\CoreBundle\Entity\Image;
use Nfq\Fairytale\CoreBundle\Entity\User;
use Nfq\Fairytale\CoreBundle\Upload\UploadManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserLoadingFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var string[][] */
    private $images = [];

    /** @var  UploadManager */
    private $uploadManager;

    /**
     * @param User   $user
     * @param string $password
     * @return string
     */
    protected function makePassword(User $user, $password)
    {
        /** @var PasswordEncoderInterface $encoder */
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        return $encoder->encodePassword($password, $user->getSalt());
    }

    /**
     * @param string    $category
     * @return Image
     */
    protected function getImage($category)
    {
        $image = new Image();
        $fs = new Filesystem();

        $sourceFile = $this->images[$category][array_rand($this->images[$category])];
        $file = new File($sourceFile);

        $tmpPath = '/tmp/' . $file->getFilename();

        $fs->copy($sourceFile, $tmpPath, true);

        $image->setFileName(preg_replace('/.*web\/(.*)/', '\1',
            $this->uploadManager->moveToStorage(new File($tmpPath), $image)->getRealPath()));

        return $image;
    }

    /**
     * @inheritdoc
     */
    function load(ObjectManager $manager)
    {
        /** @var UploadManager $uploadManager */
        $this->uploadManager = $this->container->get('fairytale_core.upload.manager');

        foreach ((new Finder())->in(__DIR__ . '/images')->directories() as $file) {
            /** @var \SplFileInfo $file */
            foreach ((new Finder())->in(__DIR__ . '/images/' . $file->getFilename())->files() as $image) {
                /** @var \SplFileInfo $image */
                $this->images[$file->getFilename()][] = $image->getPathname();
            }
        }
    }
}
