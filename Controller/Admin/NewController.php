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

namespace BaksDev\Products\Brand\Controller\Admin;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Products\Brand\Entity\Brand;
use BaksDev\Products\Brand\UseCase\Admin\NewEdit\BrandDTO;
use BaksDev\Products\Brand\UseCase\Admin\NewEdit\BrandForm;
use BaksDev\Products\Brand\UseCase\Admin\NewEdit\BrandHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[RoleSecurity(['ROLE_ADMIN', 'ROLE_BRAND_NEW'])]
final class NewController extends AbstractController
{
	#[Route('/admin/brand/new', name: 'admin.newedit.new', methods: ['GET', 'POST'])]
	public function new(
		Request $request,
		BrandHandler $brandHandler
	) : Response
	{
		
		$BrandDTO = new BrandDTO();
	
		/* Форма */
		$form = $this->createForm(BrandForm::class, $BrandDTO);
		$form->handleRequest($request);
		
		
		if($form->isSubmitted() && $form->isValid() && $form->has('brand'))
		{
			
			$Brand = $brandHandler->handle($BrandDTO);
			
			if($Brand instanceof Brand)
			{
				$this->addFlash('success', 'admin.success.new', 'admin.products.brand');
				
				return $this->redirectToRoute('Brand:admin.index');
				
			}
			
			$this->addFlash('danger', 'admin.danger.new', 'admin.products.brand', $Brand);
			
			return $this->redirectToReferer();
		}
		
		return $this->render(['form' => $form->createView()]);
	}
	
}