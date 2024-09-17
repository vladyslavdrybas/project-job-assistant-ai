<?php
declare(strict_types=1);

namespace App\Controller\AdminPanel;

use App\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/ap", name: "admin_panel")]
abstract class AbstractAdminPanelController extends AbstractController
{

}
