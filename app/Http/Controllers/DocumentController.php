<?php
/*
 * This file is part of the PayBreak/basket package.
 *
 * (c) PayBreak <dev@paybreak.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Http\Controllers;

use App\Basket\Application;
use PayBreak\Sdk\Gateways\DocumentGateway;

/**
 * Document Controller
 *
 * @author GK
 * @package App\Http\Controllers
 */
class DocumentController extends Controller
{
    /** @var  DocumentGateway */
    protected $documentGateway;

    /**
     * @author GK
     * @param DocumentGateway $documentGateway
     */
    public function __construct(DocumentGateway $documentGateway)
    {
        $this->documentGateway = $documentGateway;
    }

    /**
     * @author GK
     * @param $installationId
     * @param $applicationId
     * @return \Illuminate\Http\RedirectResponse
     * @internal param int $installation
     * @internal param int $id
     */
    public function getPreAgreement($installationId, $applicationId)
    {
        $installation = $this->fetchInstallation($installationId);

        /** @var Application $application */
        $application =  $this->fetchModelByIdWithInstallationLimit(
            (new Application()),
            $applicationId,
            'application',
            \Request::url() . '-missing'
        );

        $body = $this->documentGateway->getPreAgreementPdf(
            $installation->merchant->token,
            $installation->ext_id,
            $application->ext_id
        );

        return $this->displayPdf($body, $application->ext_id . '-pre-agreement');
    }

    /**
     * @author GK
     * @param $installationId
     * @param $applicationId
     * @return \Illuminate\Http\RedirectResponse
     * @internal param int $installation
     * @internal param int $id
     */
    public function getAgreement($installationId, $applicationId)
    {
        $installation = $this->fetchInstallation($installationId);

        /** @var Application $application */
        $application =  $this->fetchModelByIdWithInstallationLimit(
            (new Application()),
            $applicationId,
            'application',
            \Request::url() . '-missing'
        );

        $body = $this->documentGateway->getAgreementPdf(
            $installation->merchant->token,
            $installation->ext_id,
            $application->ext_id
        );

        return $this->displayPdf($body, $application->ext_id . '-agreement');
    }

    private function displayPdf($body, $name)
    {
        if (isset($body) && $body) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $name . '.pdf"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            echo base64_decode($body);

            die();
        }
        return abort(404);
    }
}
