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

use BaksDev\Contacts\Region\Entity\ContactsRegion;
use BaksDev\Contacts\Region\UseCase\Admin\NewEdit\ContactsRegionDTO;
use BaksDev\Contacts\Region\UseCase\Admin\NewEdit\ContactsRegionForm;
use BaksDev\Contacts\Region\UseCase\Admin\NewEdit\ContactsRegionHandler;
use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Delivery\UseCase\Admin\NewEdit\DeliveryDTO;
use BaksDev\Delivery\UseCase\Admin\NewEdit\DeliveryForm;
use BaksDev\Products\Brand\Entity;

use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[RoleSecurity(['ROLE_ADMIN', 'ROLE_BRAND_DELETE'])]
final class DeleteController extends AbstractController
{
	#[Route('/admin/brand/delete/{id}', name: 'admin.delete', methods: ['GET', 'POST'])]
	public function delete(
		Request $request,
		#[MapEntity] Entity\Event\BrandEvent $Event,
		//PaymentDeleteHandler $handler,
	) : Response
	{
		
		dd($Event);
		
		$PaymentDeleteDTO = new PaymentDeleteDTO();
		$Event->getDto($PaymentDeleteDTO);
		$form = $this->createForm(PaymentDeleteForm::class, $PaymentDeleteDTO, [
			'action' => $this->generateUrl('Payment:admin.delete', ['id' => $PaymentDeleteDTO->getEvent()]),
		]);
		$form->handleRequest($request);
		
		if($form->isSubmitted() && $form->isValid() && $form->has('payment_delete'))
		{
			
			$Payment = $handler->handle($PaymentDeleteDTO);
			
			if($Payment instanceof Entity\Payment)
			{
				$this->addFlash('admin.form.header.delete', 'admin.success.delete', 'admin.payment');
				
				return $this->redirectToRoute('Payment:admin.index');
			}
			
			$this->addFlash(
				'admin.form.header.delete',
				'admin.danger.delete',
				'admin.contacts.region',
				$Payment
			);
			
			return $this->redirectToRoute('Payment:admin.index', status: 400);
		}
		
		return $this->render([
			'form' => $form->createView(),
			'name' => $Event->getNameByLocale($this->getLocale()), /*  название согласно локали  */
		],
			'content.html.twig'
		);
	}
	
}