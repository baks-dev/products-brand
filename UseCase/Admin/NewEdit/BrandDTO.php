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

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Products\Brand\Entity\Event\BrandEventInterface;
use BaksDev\Products\Brand\Type\Event\BrandEventUid;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class BrandDTO implements BrandEventInterface
{
	
	/** Идентификатор события */
	#[Assert\Uuid]
	private ?BrandEventUid $id = null;
	
	/** Перевод */
	#[Assert\Valid]
	private ArrayCollection $translate;
	
	/** Настройки SEO */
	#[Assert\Valid]
	private ArrayCollection $seo;
	
	#[Assert\Valid]
	private Info\BrandInfoDTO $info;
	
	/** Обложка бренда */
	#[Assert\Valid]
	private Cover\BrandCoverDTO $cover;
	
	
	/**
	 * @param ArrayCollection $translate
	 */
	public function __construct()
	{
		$this->translate = new ArrayCollection();
		$this->seo = new ArrayCollection();
		$this->info = new Info\BrandInfoDTO();
		$this->cover = new Cover\BrandCoverDTO();
	}
	
	
	public function getEvent() : ?BrandEventUid
	{
		return $this->id;
	}
	
	
	/** Перевод */
	
	public function setTranslate(ArrayCollection $trans) : void
	{
		$this->translate = $trans;
	}
	
	
	public function getTranslate() : ArrayCollection
	{
		/* Вычисляем расхождение и добавляем неопределенные локали */
		foreach(Locale::diffLocale($this->translate) as $locale)
		{
			$BrandTransDTO = new Trans\BrandTransDTO;
			$BrandTransDTO->setLocal($locale);
			$this->addTranslate($BrandTransDTO);
		}
		
		return $this->translate;
	}
	
	
	public function addTranslate(Trans\BrandTransDTO $trans) : void
	{
		if(!$this->translate->contains($trans))
		{
			$this->translate->add($trans);
		}
	}
	
	
	public function removeTranslate(Trans\BrandTransDTO $trans) : void
	{
		$this->translate->removeElement($trans);
	}
	
	
	/** INFO */
	
	public function getInfo() : Info\BrandInfoDTO
	{
		return $this->info;
	}
	
	
	public function setInfo(Info\BrandInfoDTO $info) : void
	{
		$this->info = $info;
	}
	
	
	/** Обложка бренда */
	
	public function getCover() : Cover\BrandCoverDTO
	{
		return $this->cover;
	}
	
	
	public function setCover(Cover\BrandCoverDTO $cover) : void
	{
		$this->cover = $cover;
	}
	
	
	/* SEO  */
	
	public function addSeo(Seo\BrandSeoDTO $seo) : void
	{
		if(!$this->seo->contains($seo))
		{
			$this->seo->add($seo);
		}
	}
	
	
	public function removeSeo(Seo\BrandSeoDTO $seo) : void
	{
		$this->seo->removeElement($seo);
	}
	
	
	public function getSeo() : ArrayCollection
	{
		/* Вычисляем расхождение и добавляем неопределенные локали */
		foreach(Locale::diffLocale($this->seo) as $locale)
		{
			$BrandSeoDTO = new Seo\BrandSeoDTO();
			$BrandSeoDTO->setLocal($locale);
			$this->addSeo($BrandSeoDTO);
		}
		
		return $this->seo;
	}
	
}