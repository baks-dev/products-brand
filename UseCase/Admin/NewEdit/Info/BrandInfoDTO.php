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

namespace BaksDev\Products\Brand\UseCase\Admin\NewEdit\Info;

use BaksDev\Products\Brand\Entity\Info\BrandInfoInterface;
use BaksDev\Products\Category\Type\Id\ProductCategoryUid;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class BrandInfoDTO implements BrandInfoInterface
{
	/** Семантическая ссылка на бренд */
	#[Assert\NotBlank]
	#[Assert\Regex(
		pattern: '/^[a-z0-9\_\-]+$/i'
	)]
	private string $url;
	
	/** Флаг активности */
	private bool $active = true;
	
	#[Assert\NotBlank]
	#[Assert\Uuid]
	private ProductCategoryUid $category;
	
	
	/** Семантическая ссылка на бренд */
	
	public function getUrl() : string
	{
		return $this->url;
	}
	
	
	public function setUrl(string $url) : void
	{
		$this->url = $url;
	}
	
	
	public function updateUrlUniq() : void
	{
		$this->url = uniqid($this->url.'_', false);
	}
	
	
	/** Категория */
	
	public function setCategory(ProductCategoryUid $category) : void
	{
		$this->category = $category;
	}
	
	
	public function getCategory() : ProductCategoryUid
	{
		return $this->category;
	}
	
	
	/** Флаг активности */
	
	public function getActive() : bool
	{
		return $this->active;
	}
	
	
	public function setActive(bool $active) : void
	{
		$this->active = $active;
	}
	
}