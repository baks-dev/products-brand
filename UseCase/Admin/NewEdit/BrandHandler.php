<?php
/*
 *  Copyright 2023.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Products\Brand\UseCase\Admin\NewEdit;

use BaksDev\Files\Resources\Upload\Image\ImageUploadInterface;
use BaksDev\Products\Brand\Entity;
use BaksDev\Products\Brand\Repository\UniqBrandUrl\UniqBrandUrlInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BrandHandler
{
	private EntityManagerInterface $entityManager;
	
	private ValidatorInterface $validator;
	
	private LoggerInterface $logger;
	
	private UniqBrandUrlInterface $uniqBrandUrl;
	
	private ImageUploadInterface $imageUpload;
	
	
	public function __construct(
		EntityManagerInterface $entityManager,
		ValidatorInterface $validator,
		LoggerInterface $logger,
		UniqBrandUrlInterface $uniqBrandUrl,
		ImageUploadInterface $imageUpload,
	)
	{
		$this->entityManager = $entityManager;
		$this->validator = $validator;
		$this->logger = $logger;
		$this->uniqBrandUrl = $uniqBrandUrl;
		$this->imageUpload = $imageUpload;
	}
	
	
	public function handle(
		BrandDTO $command,
		//?UploadedFile $cover = null
	) : string|Entity\Brand
	{
		/* Валидация */
		$errors = $this->validator->validate($command);
		
		if(count($errors) > 0)
		{
			$uniqid = uniqid('', false);
			$errorsString = (string) $errors;
			$this->logger->error($uniqid.': '.$errorsString);
			
			return $uniqid;
		}
		
		if($command->getEvent())
		{
			$EventRepo = $this->entityManager->getRepository(Entity\Event\BrandEvent::class)->find(
				$command->getEvent()
			);
			
			if($EventRepo === null)
			{
				$uniqid = uniqid('', false);
				$errorsString = sprintf(
					'Not found %s by id: %s',
					Entity\Event\BrandEvent::class,
					$command->getEvent()
				);
				$this->logger->error($uniqid.': '.$errorsString);
				
				return $uniqid;
			}
			
			$Event = $EventRepo->cloneEntity();
			
		}
		else
		{
			$Event = new Entity\Event\BrandEvent();
			$this->entityManager->persist($Event);
		}
		
		$this->entityManager->clear();
		
		/** @var Entity\Brand $Main */
		if($Event->getMain())
		{
			$Main = $this->entityManager->getRepository(Entity\Brand::class)->findOneBy(
				['event' => $command->getEvent()]
			);
	
			if(empty($Main))
			{
				$uniqid = uniqid('', false);
				$errorsString = sprintf(
					'Not found %s by event: %s',
					Entity\Brand::class,
					$command->getEvent()
				);
				$this->logger->error($uniqid.': '.$errorsString);
				
				return $uniqid;
			}
			
			
			/* Получаем информацию о бренде */
			$Info = $this->entityManager->getRepository(Entity\Info\BrandInfo::class)
				->find($Main->getId())
			;
			
			
			if(empty($Info))
			{
				$uniqid = uniqid('', false);
				$errorsString = sprintf(
					'Not found %s by event: %s',
					Entity\Info\BrandInfo::class,
					$Main->getId()
				);
				$this->logger->error($uniqid.': '.$errorsString);
				
				return $uniqid;
			}
			
		}
		else
		{
			
			$Main = new Entity\Brand();
			$this->entityManager->persist($Main);
			
			$Info = new Entity\Info\BrandInfo($Main);
			$this->entityManager->persist($Info);
			
			$Event->setMain($Main);
		}
		
		/** Проверяем уникальность семантической ссылки продукта */
		$infoDTO = $command->getInfo();
		$uniqProductUrl = $this->uniqBrandUrl->exist($infoDTO->getUrl(), $Main->getId());
		
		if($uniqProductUrl)
		{
			$infoDTO->updateUrlUniq(); /* Обновляем URL на уникальный с префиксом */
		}
		
		
		
		/* Обновляем событие */
		$Event->setEntity($command);
		$this->entityManager->persist($Event);
		
		
		/* Загружаем файл обложки */
		/**  @var Cover\BrandCoverDTO $Cover */
		$Cover = $command->getCover();
		if($Cover->file !== null)
		{
			$BrandCover = $Cover->getEntityUpload();
			$this->imageUpload->upload($Cover->file, $BrandCover);
		}

		/* Обновляем информацию */
		$Info->setEntity($infoDTO); /* Обновляем BrandInfo */
		
		/* присваиваем событие корню */
		$Main->setEvent($Event);
		$this->entityManager->flush();
		
		
		return $Main;
	}
	
}