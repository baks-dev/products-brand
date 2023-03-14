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

namespace BaksDev\Products\Brand\Entity;

use BaksDev\Products\Brand\Entity\Event\BrandEvent;
use BaksDev\Products\Brand\Type\Event\BrandEventUid;
use BaksDev\Products\Brand\Type\Id\BrandUid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/* Brand */


#[ORM\Entity]
#[ORM\Table(name: 'brand')]
class Brand
{
	public const TABLE = 'brand';
	
	/** ID */
	#[ORM\Id]
	#[ORM\Column(type: BrandUid::TYPE)]
	private BrandUid $id;
	
	/** ID События */
	#[ORM\Column(type: BrandEventUid::TYPE, unique: true)]
	private BrandEventUid $event;
	
	
	public function __construct()
	{
		$this->id = new BrandUid();
	}
	
	
	public function getId() : BrandUid
	{
		return $this->id;
	}
	
	
	public function getEvent() : BrandEventUid
	{
		return $this->event;
	}
	
	
	public function setEvent(BrandEventUid|BrandEvent $event) : void
	{
		$this->event = $event instanceof BrandEvent ? $event->getId() : $event;
	}
	
}