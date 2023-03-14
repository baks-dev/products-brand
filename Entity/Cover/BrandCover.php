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

namespace BaksDev\Products\Brand\Entity\Cover;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Entity\EntityState;
use BaksDev\Files\Resources\Upload\UploadEntityInterface;
use BaksDev\Products\Brand\Entity\Event\BrandEvent;
use BaksDev\Products\Brand\Type\Event\BrandEventUid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/* BrandCover */


#[ORM\Entity]
#[ORM\Table(name: 'brand_cover')]
class BrandCover extends EntityEvent implements UploadEntityInterface
{
	public const TABLE = 'brand_cover';
	
	/** Связь на событие */
	#[ORM\Id]
	#[ORM\OneToOne(inversedBy: 'cover', targetEntity: BrandEvent::class)]
	#[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
	private BrandEvent $event;
	
	/** Название директории по идентификатору события */
	#[ORM\Column(type: BrandEventUid::TYPE)]
	private BrandEventUid $dir;
	
	/** Название файла */
	#[ORM\Column(type: Types::STRING, length: 100)]
	private string $name;
	
	/** Расширение файла */
	#[ORM\Column(type: Types::STRING, length: 64)]
	private string $ext;
	
	/** Размер файла */
	#[ORM\Column(type: Types::INTEGER)]
	private int $size = 0;
	
	/** Файл загружен на CDN */
	#[ORM\Column(type: Types::BOOLEAN)]
	private bool $cdn = false;
	
	
	public function __construct(BrandEvent $event) { $this->event = $event; }
	
	
	public function getDto($dto) : mixed
	{
		if($dto instanceof BrandCoverInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function setEntity($dto) : mixed
	{
		/* Если размер файла нулевой - не заполняем сущность */
		if(empty($dto->file) && empty($dto->getName()))
		{
			return false;
		}
		
		if(!empty($dto->file))
		{
			$dto->setEntityUpload($this);
		}
		
		if($dto instanceof BrandCoverInterface)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function updFile(string $name, string $ext, int $size) : void
	{
		$this->name = $name;
		$this->ext = $ext;
		$this->size = $size;
		$this->dir = $this->event->getId();
		$this->cdn = false;
	}
	
	
	public function updCdn(string $ext) : void
	{
		$this->ext = $ext;
		$this->cdn = true;
	}
	
	
	public function getId() : BrandEventUid
	{
		return $this->event->getId();
	}
	
	
	public function getUploadDir() : object
	{
		return $this->event->getId();
	}
	
}