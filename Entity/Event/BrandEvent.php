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

namespace BaksDev\Products\Brand\Entity\Event;

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Core\Type\Modify\ModifyActionEnum;
use BaksDev\Products\Brand\Entity\Brand;
use BaksDev\Products\Brand\Entity\Cover\BrandCover;
use BaksDev\Products\Brand\Entity\Modify\BrandModify;
use BaksDev\Products\Brand\Entity\Seo\BrandSeo;
use BaksDev\Products\Brand\Entity\Trans\BrandTrans;
use BaksDev\Products\Brand\Type\Event\BrandEventUid;
use BaksDev\Products\Brand\Type\Id\BrandUid;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Entity\EntityState;
use InvalidArgumentException;

/* BrandEvent */


#[ORM\Entity]
#[ORM\Table(name: 'brand_event')]
class BrandEvent extends EntityEvent
{
	public const TABLE = 'brand_event';
	
	/** ID */
	#[ORM\Id]
	#[ORM\Column(type: BrandEventUid::TYPE)]
	private BrandEventUid $id;
	
	/** ID Brand */
	#[ORM\Column(type: BrandUid::TYPE, nullable: false)]
	private ?BrandUid $main = null;
	
	/** One To One */
	#[ORM\OneToOne(mappedBy: 'event', targetEntity: BrandCover::class, cascade: ['all'])]
	private ?BrandCover $cover = null;
	
	/** Модификатор */
	#[ORM\OneToOne(mappedBy: 'event', targetEntity: BrandModify::class, cascade: ['all'])]
	private BrandModify $modify;
	
	/** Перевод */
	#[ORM\OneToMany(mappedBy: 'event', targetEntity: BrandTrans::class, cascade: ['all'])]
	private Collection $translate;
	
	/** SEO */
	#[ORM\OneToMany(mappedBy: 'event', targetEntity: BrandSeo::class, cascade: ['all'])]
	private Collection $seo;
	
	public function __construct()
	{
		$this->id = new BrandEventUid();
		$this->modify = new BrandModify($this);
		
	}
	
	
	public function __clone()
	{
		$this->id = new BrandEventUid();
	}
	
	
	public function __toString() : string
	{
		return (string) $this->id;
	}
	
	
	public function getId() : BrandEventUid
	{
		return $this->id;
	}
	
	
	public function setMain(BrandUid|Brand $main) : void
	{
		$this->main = $main instanceof Brand ? $main->getId() : $main;
	}
	
	
	public function getMain() : ?BrandUid
	{
		return $this->main;
	}
	
	
	public function getDto($dto) : mixed
	{
		if($dto instanceof BrandEventInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function setEntity($dto) : mixed
	{
		if($dto instanceof BrandEventInterface)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	//	public function isModifyActionEquals(ModifyActionEnum $action) : bool
	//	{
	//		return $this->modify->equals($action);
	//	}
	
	//	public function getUploadClass() : BrandImage
	//	{
	//		return $this->image ?: $this->image = new BrandImage($this);
	//	}
	
	//	public function getNameByLocale(Locale $locale) : ?string
	//	{
	//		$name = null;
	//
	//		/** @var BrandTrans $trans */
	//		foreach($this->translate as $trans)
	//		{
	//			if($name = $trans->name($locale))
	//			{
	//				break;
	//			}
	//		}
	//
	//		return $name;
	//	}
}