<?php
/*
 *  Copyright 2022.  Baks.dev <admin@baks.dev>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *   limitations under the License.
 *
 */

namespace BaksDev\Products\Brand\Entity\Seo;


use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Products\Brand\Entity\Event\BrandEvent;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity]
#[ORM\Table(name: 'brand_seo')]
class BrandSeo extends EntityEvent
{
	public const TABLE = "brand_seo";
	
	/** Связь на событие */
	#[ORM\Id]
	#[ORM\ManyToOne(targetEntity: BrandEvent::class, inversedBy: "seo")]
	#[ORM\JoinColumn(name: 'event', referencedColumnName: "id")]
	private BrandEvent $event;
	
	/** Локаль */
	#[ORM\Id]
	#[ORM\Column(type: Locale::TYPE, length: 2, nullable: false)]
	private Locale $local;
	
	/** Шаблон META TITLE */
	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $title;
	
	/** Шаблон META KEYWORDS */
	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $keywords;
	
	/** Шаблон META DESCRIPTION */
	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $description;
	
	
	public function __construct(BrandEvent $event) { $this->event = $event; }
	
	
	public function getDto($dto) : mixed
	{
		if($dto instanceof BrandSeoInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function setEntity($dto) : mixed
	{
		
		if(empty($dto->getTitle()) && empty($dto->getDescription()) && empty($dto->getKeywords()))
		{
			return false;
		}
		
		if($dto instanceof BrandSeoInterface)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
}