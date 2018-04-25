<?php

namespace User\Controller\ConfirmEmail;

use Application\Service\MailService;
use User\Model\UserModel;
use Util\Logger\PPLog;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class ConfirmEmailController
 *
 * @package User\Controller\ConfirmEmail
 */
class ConfirmEmailController extends AbstractActionController
{
    /**
     * @var UserModel
     */
    private $model;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * RegistrationController constructor.
     *
     * @param UserModel   $model
     * @param MailService $mailService
     */
    public function __construct(
        UserModel $model,
        MailService $mailService
    ) {
        $this->model       = $model;
        $this->mailService = $mailService;
    }

    /**
     * @return ApiProblemResponse|Response
     */
    public function confirmAction()
    {
        try {
            $code  = $this->params()->fromQuery('code');
            $email = urldecode($this->params()->fromQuery('email'));
            if (empty($code)) {
                return $this->redirect()->toRoute('sorry-page');
            }
            if (empty($email)) {
                return $this->redirect()->toRoute('sorry-page');
            }

            $user = $this->model->activateUser($code, $email);
            if (empty($user)) {
                return $this->redirect()->toRoute('sorry-page');
            }

            $this->mailService
                ->setSubject('Регитсрация подтверждена')
                ->addTo($user->getUsername())
                ->assembleBody('user/mail/confirmed-email');

            $this->mailService->send();

            return $this->redirect()->toRoute('user/thank-page');
        } catch (\Exception $exception) {
            PPLog::exception($exception);

            return $this->redirect()->toRoute('sorry-page');
        }
    }
}
