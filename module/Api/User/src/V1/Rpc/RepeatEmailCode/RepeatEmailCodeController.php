<?php

namespace Api\User\V1\Rpc\RepeatEmailCode;

use Application\Service\MailService;
use Application\Util\InputFilterExtractorTrait;
use Application\Util\ProblemResponseTrait;
use DateTime;
use User\Entity\User;
use User\Entity\UserCode;
use User\Enum\CodeType;
use User\Enum\UserStatus;
use User\Model\UserModel;
use Util\Logger\PPLog;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class RepeatEmailCodeController
 *
 * @package Api\User\V1\Rpc\RepeatEmailCode
 */
class RepeatEmailCodeController extends AbstractActionController
{
    use ProblemResponseTrait, InputFilterExtractorTrait;

    /**
     * @var UserModel
     */
    private $model;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * RepeatEmailCodeController constructor.
     *
     * @param UserModel   $model
     * @param MailService $mailService
     */
    public function __construct(UserModel $model, MailService $mailService)
    {
        $this->model       = $model;
        $this->mailService = $mailService;
    }

    /**
     * @return ApiProblemResponse|Response
     */
    public function repeatCodeAction()
    {
        try {
            $inputFilter = $this->getInputFilter($this->getEvent());
            $email       = $inputFilter->getValue('email');
            $user        = $this->model->findOneBy(['username' => $email]);
            if (empty($user) || $user->getStatus() != UserStatus::SUSPEND) {
                return $this->problemResponse(Response::STATUS_CODE_403, 'Non personalized requests are forbidden');
            }

            $lastCode = $this->getLastCode($user);
            if (!empty($lastCode) && $lastCode->getCreatedAt() > (new DateTime())->modify("-10 minutes")) {
                return $this->problemResponse(Response::STATUS_CODE_429, 'Interval is 10 minute');
            }

            $this->mailService
                ->setSubject('Подтверждение регистрации')
                ->addTo($user->getUsername())
                ->assembleBody(
                    'user/mail/send-confirm-link',
                    [
                        'user' => $user,
                        'link' => $this->model->createConfirmLink(
                            $user, empty($lastCode) ? null : $lastCode->getCode()
                        ),
                    ]
                );
            $this->mailService->send();

            $response = new Response();
            $response->setStatusCode(Response::STATUS_CODE_204);

            return $response;
        } catch (\Exception $exception) {
            PPLog::exception($exception);

            return $this->problemResponse(Response::STATUS_CODE_500, 'Error occurred during receive email code');
        }
    }

    /**
     * @param $user
     *
     * @return null|UserCode
     */
    protected function getLastCode(User $user)
    {
        /** @var UserCode $lastCode */
        $lastCode = null;
        foreach ($user->getCodes() as $code) {
            $isLast = empty($lastCode) || $code->getCreatedAt() > $lastCode->getCreatedAt();
            if ($code->getType() == CodeType::EMAIL && $isLast) {
                $lastCode = $code;
            }
        }

        return $lastCode;
    }
}
