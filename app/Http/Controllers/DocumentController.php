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
    const PREAGREEMENT = 'pre-agreement';
    const AGREEMENT = 'agreement';
    const ADEQUATE_EXPLANATION = 'adequate-explanation';

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
        return $this->getPdf($installationId, $applicationId, self::PREAGREEMENT);
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
        return $this->getPdf($installationId, $applicationId, self::AGREEMENT);
    }

    /**
     * @author EB
     * @param $installationId
     * @param $applicationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAdequateExplanation($installationId, $applicationId)
    {
        return $this->getPdf($installationId, $applicationId, self::ADEQUATE_EXPLANATION);
    }

    /**
     * @author EB
     * @param $installationId
     * @param $applicationId
     * @return array
     */
    public function getAvailableDocuments($installationId, $applicationId)
    {
        $installation = $this->fetchInstallation($installationId);

        /** @var Application $application */
        $application =  $this->fetchModelByIdWithInstallationLimit(
            (new Application()),
            $applicationId,
            'application',
            'installations/' . $installation . '/applications'
        );

        return $this->documentGateway->getAvailableDocuments(
            $installation->merchant->token,
            $installation->ext_id,
            $application->ext_id
        );
    }

    /**
     * @author GK, EB
     * @param int $installationId
     * @param int $applicationId
     * @param string $type
     * @return bool|void
     */
    private function getPdf($installationId, $applicationId, $type)
    {
        try {
            $installation = $this->fetchInstallation($installationId);

            /** @var Application $application */
            $application =  $this->fetchModelByIdWithInstallationLimit(
                (new Application()),
                $applicationId,
                'application',
                'installations/' . $installation . '/applications'
            );

            $body = null;
            switch ($type) {
                case self::PREAGREEMENT:
                    $body = $this->documentGateway->getPreAgreementPdf(
                        $installation->merchant->token,
                        $installation->ext_id,
                        $application->ext_id
                    );
                    break;
                case self::AGREEMENT:
                    $body = $this->documentGateway->getAgreementPdf(
                        $installation->merchant->token,
                        $installation->ext_id,
                        $application->ext_id
                    );
                    break;
                case self::ADEQUATE_EXPLANATION:
                    $body = $this->documentGateway->getAdequateExplanation(
                        $installation->merchant->token,
                        $installation->ext_id,
                        $application->ext_id
                    );
                    break;
                default:
                    return abort(404);
            }

            return $this->displayPdf($body, $application->ext_id . '-' . $type);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    /**
     * @author GK
     * @param string $body
     * @param string $name
     * @return bool|void
     */
    private function displayPdf($body, $name)
    {
        if (isset($body) && $body) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $name . '.pdf"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            echo base64_decode($body);

            return true;
        }
        return abort(404);
    }
}
