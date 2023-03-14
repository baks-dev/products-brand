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

namespace BaksDev\Products\Brand\Entity\Info;

use BaksDev\Core\Entity\EntityState;
use BaksDev\Products\Brand\Entity\Brand;
use BaksDev\Products\Brand\Entity\Info\Category\BrandCategory;
use BaksDev\Products\Brand\Type\Id\BrandUid;
use BaksDev\Products\Category\Type\Id\ProductCategoryUid;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use InvalidArgumentException;


/* BrandInfo */


#[ORM\Entity]
#[ORM\Table(name: 'brand_info')]
#[ORM\Index(columns: ['category'])]
class BrandInfo extends EntityState
{
	public const TABLE = 'brand_info';
	
	/** ID */
	#[ORM\Id]
	#[ORM\Column(type: BrandUid::TYPE)]
	private BrandUid $brand;
	
	/** Семантическая ссылка на бренд */
	#[ORM\Column(type: Types::STRING, unique: true)]
	private string $url;
	
	/** Категория */
	#[ORM\Column(type: ProductCategoryUid::TYPE)]
	private ProductCategoryUid $category;
	
	/** Флаг активности */
	#[ORM\Column(type: Types::BOOLEAN)]
	private bool $active = true;
	
	
	public function __construct(Brand|BrandUid $brand)
	{
		$this->brand = $brand instanceof Brand ? $brand->getId() : $brand;
	}
	
	
	public function getBrand() : BrandUid
	{
		return $this->brand;
	}
	
	
	
	public function getDto($dto) : mixed
	{
		if($dto instanceof BrandInfoInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function setEntity($dto) : mixed
	{
		if($dto instanceof BrandInfoInterface)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
}