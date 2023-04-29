<?php

namespace App\Controller;
use Endroid\QrCode\Builder\BuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class qrcode extends AbstractController
{

#[Route('/qrcode', name: 'appqrcode')]
public function qrcode(BuilderInterface $builderInterface,string $code)
    { $qrResult= $builderInterface
    ->size(200)
    ->margin(20)
    ->data($code)
    ->build();

    $qrResult->saveToFile((\dirname (__DIR__, 2) .'/public/assets/img/qr-codes/qrcode.png'));
    
    return $qrResult->getDataUri();

 }

}