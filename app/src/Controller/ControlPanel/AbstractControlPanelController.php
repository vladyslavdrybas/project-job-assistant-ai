<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/cp", name: "control_panel")]
abstract class AbstractControlPanelController extends AbstractController
{

}
