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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Products\Brand\Entity\Cover\BrandCover;
use BaksDev\Products\Product\Entity\Files\ProductFiles;
use BaksDev\Products\Product\Entity\Offers\Image\ProductOfferImage;
use BaksDev\Products\Product\Entity\Offers\Variation\Image\ProductOfferVariationImage;
use BaksDev\Products\Product\Entity\Offers\Variation\Modification\Image\ProductOfferVariationModificationImage;
use BaksDev\Products\Product\Entity\Photo\ProductPhoto;
use BaksDev\Products\Product\Entity\Video\ProductVideo;
use Symfony\Config\TwigConfig;

return static function(TwigConfig $config, ContainerConfigurator $configurator) {
	
	$config->path(__DIR__.'/../view', 'ProductBrand');
	
	
	/** ОБЛОЖКИ брендов */
	
	/* Абсолютный Путь для загрузки обложек товара галереи */
	$configurator->parameters()->set(BrandCover::TABLE,
		'%kernel.project_dir%/public/upload/'.BrandCover::TABLE.'/'
	);
	
	/* Относительный путь обложек товара галереи */
	$config->global(BrandCover::TABLE)->value('/upload/'.BrandCover::TABLE.'/');
	
	
};




